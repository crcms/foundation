<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-04-13 21:08
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Foundation\Resources\Concerns;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Trait IncludeConcern
 * @package CrCms\Foundation\Resources
 */
trait IncludeConcern
{
    /**
     * @var array
     */
    protected $includes = [];

    /**
     * @var array
     */
    protected $parseIncludes = [];

    /**
     * @var string
     */
    protected $includeRequestKey = 'includes';

    /**
     * @param array $includes
     * @return self
     */
    public function setIncludes(array $includes): self
    {
        $this->includes = $includes;

        return $this;
    }

    /**
     * @param string $include
     * @return IncludeTrait
     */
    public function addInclude(string $include): self
    {
        if (!in_array($include, $this->includes, true)) {
            $this->includes[] = $include;
        }

        return $this;
    }

    /**
     * @param string $include
     * @return IncludeTrait
     */
    public function removeInclude(string $include): self
    {
        $key = array_search($include, $this->includes);
        if ($key) {
            unset($this->includes[$key]);
        }

        return $this;
    }

    /**
     * @param array $includes
     * @return IncludeTrait
     */
    public function removeIncludes(array $includes): self
    {
        array_map(function ($include) {
            $this->removeInclude($include);
        }, $includes);

        return $this;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function includes($request): array
    {
        $includes = array_merge($this->parseIncludeParams($request), $this->includes);

        //原有属性兼容
        if (property_exists($this, 'defaultIncludes')) {
            $includes = array_merge($includes, $this->defaultIncludes);
        }

        return array_unique($includes);
    }

    /**
     * @param Request $request
     * @return array
     */
    protected function parseIncludeParams($request): array
    {
        $includes = $request->input($this->includeRequestKey, []);
        return is_array($includes) ? $includes : explode(',', $includes);
    }

    /**
     * @param array $includes
     * @param $request
     * @return Collection
     */
    protected function parseIncludes(array $includes, $request): Collection
    {
        $diffIncludes = array_diff_key($includes, $this->parseIncludes);

        if (!empty($diffIncludes)) {
            $diffIncludes = (new Collection($diffIncludes))->map(function ($include) {
                return [
                    'key' => $include,
                    'method' => Str::camel("include-{$include}"),
                ];
            })->filter(function ($include) {
                return method_exists($this, $include['method']);
            })->mapWithKeys(function ($include) use ($request) {
                $resource = call_user_func([$this, $include['method']], $request);
                return [$include['key'] => $resource];
            })->all();

            $this->parseIncludes = array_merge($diffIncludes, $this->parseIncludes);
        }

        return Collection::make(Arr::only($this->parseIncludes, $includes));
    }

    /**
     * @param array $includes
     * @param Request $request
     * @return array
     */
    protected function execIncludes(array $includes, $request): array
    {
        return $this->parseIncludes($includes, $request)->map(function ($item) use ($request) {
            return $this->resolveIncludeResource($request, $item);
        })->all();
    }

    /**
     * @param Request $request
     * @param $resource
     * @return mixed
     */
    protected function resolveIncludeResource($request, $resource)
    {
        if ($resource instanceof Resource) {
            $resource = $resource->resolve($request);
        } elseif ($resource instanceof ResourceCollection) {
            $resource = $resource->resource instanceof AbstractPaginator ?
                Arr::only($resource->toResponse($request)->getData(true), [$resource::$wrap, 'links', 'meta']) :
                $resource->resolve($request);
        }

        return $resource;
    }
}