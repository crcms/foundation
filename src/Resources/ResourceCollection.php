<?php

namespace CrCms\Foundation\Resources;

use CrCms\Foundation\Resources\Concerns\WhenMakeConcern;
use CrCms\Foundation\Resources\Concerns\SceneConcern;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection as BaseResourceCollection;

/**
 * Class ResourceCollection.
 */
class ResourceCollection extends BaseResourceCollection
{
    use SceneConcern, WhenMakeConcern;

    /**
     * ResourceCollection constructor.
     * @param $resource
     * @param string $collect
     */
    public function __construct($resource, ?string $collect = null)
    {
        $this->collects = $collect ? $collect : get_called_class();
        parent::__construct($resource);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return $this->collection->map->scene($this->scene)->fields($this->fields)->toArray($request)->all();
    }

    /**
     *
     * @return array
     */
    protected static function emptyReturn(): array
    {
        return [];
    }
}
