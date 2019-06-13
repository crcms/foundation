<?php

namespace CrCms\Foundation\Response;

use Traversable;
use DomainException;
use JsonSerializable;
use Illuminate\Http\Response;
use InvalidArgumentException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use CrCms\Foundation\Resources\Resource;
use Illuminate\Support\Traits\ForwardsCalls;
use CrCms\Foundation\Resources\ResourceCollection;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Laravel\Lumen\Http\ResponseFactory as LumenResponseFactory;
use Illuminate\Contracts\Routing\ResponseFactory as FactoryContract;

class Factory
{
    use ForwardsCalls;

    /**
     * @var FactoryContract
     */
    protected $factory;

    /**
     * @param FactoryContract|LumenResponseFactory $factory
     *
     * @return $this
     */
    public function setFactory($factory)
    {
        if ((! $factory instanceof FactoryContract) && (! $factory instanceof LumenResponseFactory)) {
            throw new DomainException('The factory not allow');
        }

        $this->factory = $factory;

        return $this;
    }

    /**
     * @param null $location
     * @param null $content
     * @return Response
     */
    public function created($location = null, $content = null): Response
    {
        $response = new Response($content);
        $response->setStatusCode(201);

        if (! is_null($location)) {
            $response->header('Location', $location);
        }

        return $response;
    }

    /**
     * @param null $location
     * @param null $content
     * @return Response
     */
    public function accepted($location = null, $content = null): Response
    {
        $response = new Response($content);
        $response->setStatusCode(202);

        if (! is_null($location)) {
            $response->header('Location', $location);
        }

        return $response;
    }

    /**
     * @return Response
     */
    public function noContent(): Response
    {
        $response = new Response(null);

        return $response->setStatusCode(204);
    }

    /**
     * @param $collection
     * @param string $collect
     * @param array $fields
     * @param array $includes
     * @return JsonResponse
     */
    public function collection($collection, string $collect = '', array $fields = [], array $includes = []): JsonResponse
    {
        if (! $collection instanceof ResourceCollection && class_exists($collect)) {
            if (substr($collect, -8) === 'Resource') {
                $collection = call_user_func([$collect, 'collection'], $collection);
            } elseif (substr($collect, -10) === 'Collection') {
                $collection = (new $collect($collection));
            } else {
                throw new InvalidArgumentException('Non-existent resource converter');
            }
        }

        if (! $collection instanceof ResourceCollection) {
            throw new InvalidArgumentException('Non-existent resource converter');
        }

        return $this->resourceToResponse($collection, $fields, $includes);
    }

    /**
     * @param $resource
     * @param string $collect
     * @param array $fields
     * @param array $includes
     * @return JsonResponse
     */
    public function resource($resource, string $collect = '', array $fields = [], array $includes = []): JsonResponse
    {
        if (! $resource instanceof Resource && class_exists($collect)) {
            $resource = (new $collect($resource));
        }

        if (! $resource instanceof Resource) {
            throw new InvalidArgumentException('Non-existent resource converter');
        }

        return $this->resourceToResponse($resource, $fields, $includes);
    }

    /**
     * @param $paginator
     * @param string $collect
     * @param array $fields
     * @param array $includes
     * @return JsonResponse
     */
    public function paginator($paginator, string $collect = '', array $fields = [], array $includes = []): JsonResponse
    {
        return $this->collection($paginator, $collect, $fields, $includes);
    }

    /**
     * @param string $message
     * @param int $statusCode
     *
     * @throws HttpException
     *
     * @return void
     */
    public function error(string $message, int $statusCode): void
    {
        throw new HttpException($statusCode, $message);
    }

    /**
     * @param string $message
     *
     * @return void
     */
    public function errorNotFound(string $message = 'Not Found'): void
    {
        $this->error($message, 404);
    }

    /**
     * @param string $message
     *
     * @return void
     */
    public function errorBadRequest(string $message = 'Bad Request'): void
    {
        $this->error($message, 400);
    }

    /**
     * Return a 403 forbidden error.
     *
     * @param string $message
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     *
     * @return void
     */
    public function errorForbidden(string $message = 'Forbidden'): void
    {
        $this->error($message, 403);
    }

    /**
     * Return a 500 internal server error.
     *
     * @param string $message
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     *
     * @return void
     */
    public function errorInternal(string $message = 'Internal Error'): void
    {
        $this->error($message, 500);
    }

    /**
     * Return a 401 unauthorized error.
     *
     * @param string $message
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     *
     * @return void
     */
    public function errorUnauthorized(string $message = 'Unauthorized'): void
    {
        $this->error($message, 401);
    }

    /**
     * Return a 405 method not allowed error.
     *
     * @param string $message
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     *
     * @return void
     */
    public function errorMethodNotAllowed(string $message = 'Method Not Allowed'): void
    {
        $this->error($message, 405);
    }

    /**
     * @param array $array
     *
     * @return JsonResponse
     */
    public function array(array $array): JsonResponse
    {
        return new JsonResponse($array);
    }

    /**
     * @param array|Collection|JsonSerializable|Traversable|mixed $data
     * @param string $key
     * @return JsonResponse
     */
    public function data($data, string $key = 'data'): JsonResponse
    {
        if ($data instanceof Collection) {
            $data = $data->all();
        } elseif ($data instanceof JsonSerializable) {
            $data = $data->jsonSerialize();
        } elseif ($data instanceof Traversable) {
            $data = iterator_to_array($data);
        } elseif (is_object($data)) {
            $data = get_object_vars($data);
        }

        return $this->array([$key => $data]);
    }

    /**
     * @return JsonResponse
     */
    public function null(): JsonResponse
    {
        return new JsonResponse();
    }

    /**
     * @param ResourceCollection|resource $resource
     * @param array $fields
     * @param array $includes
     * @return JsonResponse
     */
    protected function resourceToResponse($resource, array $fields, array $includes = []): JsonResponse
    {
        if ($includes && method_exists($resource, 'setIncludes')) {
            $resource->setIncludes($includes);
        }

        if (isset($fields['scene'])) {
            $type = 'scene';
            $fields = $fields['scene'];
        } elseif (isset($fields['only'])) {
            $type = 'only';
            $fields = $fields['only'];
        } elseif (isset($fields['except']) || isset($fields['hide'])) {
            $type = 'except';
            $fields = $fields['except'] ?? $fields['hide'];
        } else {
            $type = 'except';
        }

        return $resource->$type($fields)->response();
    }

    /**
     * @param string $method
     * @param array $parameters
     *
     * @return mixed
     */
    public function __call(string $method, array $parameters)
    {
        return $this->forwardCallTo($this->factory, $method, $parameters);
    }
}
