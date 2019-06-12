<?php

namespace CrCms\Foundation\Resources;

use CrCms\Foundation\Resources\Concerns\SceneConcern;
use CrCms\Foundation\Resources\Concerns\TypeConcern;
use Illuminate\Support\Collection;
use Illuminate\Http\Resources\Json\JsonResource as BaseResource;
use Illuminate\Support\Str;

class JsonResource extends BaseResource
{
    use SceneConcern, TypeConcern;

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        if (is_null($this->resource)) {
            return [];
        }

        return $this->resolveFields($request);
    }

    /**
     * @param $request
     *
     * @return array
     */
    protected function resolveFields($request): array
    {
        $fields = $this->scene ? $this->sceneFields($this->scene) : $this->fields;

        return Collection::make($fields)->mapWithKeys(function ($field, $key) use ($request) {
            //alias
            if (is_integer($key)) {
                $alias = $field;
            } else {
                $alias = $field;
                $field = $key;
            }
            $callName = Str::studly("get_{$field}_field");
            if (method_exists($this, $callName)) {
                $value = call_user_func([$this, $callName], $request);
            } elseif (is_array($this->resource) && isset($this->resource[$field])) {
                $value = $this->resource[$field];
            } elseif (is_object($this->resource) && isset($this->resource->{$field})) {
                $value = $this->resource->{$field};
            } else {
                //default value
                $value = '';
            }

            return [$alias => $this->conversion($value, $field)];
        })->toArray();
    }

    /**
     * Create new anonymous resource collection.
     *
     * @param  mixed $resource
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public static function collection($resource)
    {
        return tap(new ResourceCollection($resource, static::class), function ($collection) {
            if (property_exists(static::class, 'preserveKeys')) {
                $collection->preserveKeys = (new static([]))->preserveKeys === true;
            }
        });
    }
}
