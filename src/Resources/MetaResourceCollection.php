<?php

namespace CrCms\Foundation\Resources;

use Illuminate\Http\Request;

/**
 * Class MetaResourceCollection
 * @package CrCms\Foundation\Resources
 */
class MetaResourceCollection extends ResourceCollection
{
    /**
     * @param Request $request
     * @return array
     */
    public function with($request)
    {
        $first = $this->collection->first();

        if (!empty($first) && is_object($first) && method_exists($first,'bindHeadingsToMeta')) {
            $this->with['headings'] = $first->bindHeadingsToMeta($request);
            $this->with['condition'] = $first->bindConditionToMeta($request);
        }

        return $this->with;
    }
}