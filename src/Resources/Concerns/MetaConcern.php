<?php

namespace CrCms\Foundation\Resources\Concerns;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

/**
 * Trait MetaConcern
 * @package App\Modules\Support\Http\Api\Resources
 */
trait MetaConcern
{
    /**
     * @var bool
     */
    protected $supportHeadingBindFilter = true;

    /**
     * @param bool $support
     * @return MetaConcern
     */
    public function setSupportHeadingBindFilter(bool $support): self
    {
        $this->supportHeadingBindFilter = $support;

        return $this;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function getAllowFields($request): array
    {
        if (method_exists($this, 'allowFields')) {
            return call_user_func([$this, 'allowFields']);
        }

        return array_keys($this->headings($request));
    }

    /**
     * @param Request $request
     * @return array
     */
    public function getAllowCondition($request): array
    {
        if (method_exists($this, 'allowCondition')) {
            return call_user_func([$this, 'allowCondition']);
        }

        return Collection::make($this->condition($request))->map->name->toArray();
    }

    /**
     * @param $resource
     * @return ResourceCollection
     */
    public static function collection($resource)
    {
        return new ResourceCollection($resource, get_called_class());
    }

    /**
     * @param Request $request
     * @return array
     */
    abstract public function headings($request): array;

    /**
     * @param Request $request
     * @return array
     */
    abstract public function condition($request): array;

    /**
     * @param $request
     * @return array
     */
    public function with($request)
    {
        $this->with['headings'] = $this->bindHeadingsToMeta($request);
        $this->with['condition'] = $this->bindConditionToMeta($request);
        return $this->with;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function bindHeadingsToMeta($request): array
    {
        $headings = Arr::only($this->headings($request), $this->getAllowFields($request));

        if ($this->supportHeadingBindFilter) {
            $includeHeadings = $this->headingIncludeBindFilter($request);
            return $this->filterFields(array_merge($headings, $includeHeadings));
        } else {
            $includeHeadings = $this->parseIncludeMeta($this->includes($request), $request, 'bindHeadingsToMeta');
            return array_merge($headings, $includeHeadings);
        }
    }

    /**
     * @param Request $request
     * @return array
     */
    public function bindConditionToMeta($request): array
    {
        $allowConditions = $this->getAllowCondition($request);

        $conditions = Collection::make($this->condition($request))->filter(function ($item) use ($allowConditions) {
            return in_array($item['name'], $allowConditions, true);
        })->toArray();

        $includeConditions = $this->parseIncludeMeta($this->includes($request), $request, 'bindConditionToMeta');

        return array_merge($conditions, $includeConditions);
    }

    /**
     * @param Request $request
     * @return array
     */
    protected function headingIncludeBindFilter($request): array
    {
        $includes = $this->includes($request);

        if (property_exists($this, 'resourceFields') && property_exists($this, 'resourceType') && !empty($this->resourceFields)) {
            //合并includes到字段的过滤
            if ($this->resourceFields && $includes) {
                $func = $this->resourceType === 'only' ? 'array_intersect' : 'array_diff';
                $includes = call_user_func($func, $includes, $this->resourceFields);
            }
        }

        return $this->parseIncludeMeta($includes, $request, 'bindHeadingsToMeta');
    }

    /**
     * @param array $includes
     * @param Request $request
     * @param string $bindType
     * @return array
     */
    protected function parseIncludeMeta(array $includes, $request, string $bindType): array
    {
        $type = ['bindHeadingsToMeta' => 'headings', 'bindConditionToMeta' => 'condition'];

        return $this->parseIncludes($includes, $request)->filter(function ($item) use ($bindType, $request, $type) {
            return ($item instanceof ResourceCollection || $item instanceof Resource);
        })->map(function ($item) use ($bindType, $request, $type) {
            return $item->with($request)[$type[$bindType]] ?? [];
        })->filter(function ($item) {
            return !empty($item);
        })->toArray();
    }
}