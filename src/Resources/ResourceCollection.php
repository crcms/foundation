<?php

namespace CrCms\Foundation\Resources;

use Illuminate\Http\Request;
use CrCms\Foundation\Resources\Concerns\FieldConcern;
use CrCms\Foundation\Resources\Concerns\IncludeConcern;
use Illuminate\Http\Resources\Json\ResourceCollection as BaseResourceCollection;

/**
 * Class ResourceCollection.
 */
class ResourceCollection extends BaseResourceCollection
{
    use FieldConcern, IncludeConcern;

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
        return $this->collection->map(function (Resource $resource) use ($request) {
            if ($this->includes) {
                $resource->setIncludes($this->includes);
            }

            $resource = ($this->resourceType === 'scene' ?
                $resource->{$this->resourceType}($this->scene) :
                $resource->{$this->resourceType}($this->resourceFields));

            return $resource->resolve($request);
        })->all();
    }
}
