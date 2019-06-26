<?php

namespace CrCms\Foundation\Resources\V2;

use Illuminate\Http\Request;
use CrCms\Foundation\Resources\V2\Concerns\SceneConcern;
use Illuminate\Http\Resources\Json\ResourceCollection as BaseResourceCollection;

/**
 * Class ResourceCollection.
 */
class ResourceCollection extends BaseResourceCollection
{
    use SceneConcern;

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
        return $this->collection->map(function($item){
            if ($this->scene) {
                $item->scene($this->scene);
            }
            if ($this->fields) {
                $item->fields($this->fields);
            }
            return $item;
        })->toArray($request);
    }
}
