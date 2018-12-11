<?php

namespace CrCms\Foundation\Resources;

use CrCms\Foundation\Resources\Concerns\FieldConcern;
use CrCms\Foundation\Resources\Concerns\IncludeConcern;
use Illuminate\Container\Container;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\Resource as BaseResource;
use Illuminate\Support\Collection;

class Resource extends BaseResource
{
    use IncludeConcern, FieldConcern;

    /**
     * @param null $request
     * @return array
     */
    public function resolve($request = null)
    {
        $request = $request ?: Container::getInstance()->make('request');

        $data = $this->filterFields(
            $this->mergeIncludeData($request)
        );

        if (is_array($data)) {
            $data = $data;
        } elseif ($data instanceof Arrayable || $data instanceof Collection) {
            $data = $data->toArray();
        } elseif ($data instanceof \JsonSerializable) {
            $data = $data->jsonSerialize();
        }

        return $this->filter((array)$data);
    }

    /**
     * @param Request $request
     * @return array
     */
    protected function mergeIncludeData($request): array
    {
        $includes = $this->includes($request);

        //合并includes到字段的过滤
        if ($this->resourceFields && $includes) {
            $func = $this->resourceType === 'only' ? 'array_intersect' : 'array_diff';
            $includes = call_user_func($func, $includes, $this->resourceFields);
        }

        return array_merge(
            $this->toArray($request),
            $this->execIncludes($includes, $request)
        );
    }

    /**
     * @param mixed $resource
     * @return ResourceCollection|AnonymousResourceCollection
     */
    public static function collection($resource)
    {
        return new ResourceCollection($resource, get_called_class());
    }
}