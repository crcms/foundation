<?php

namespace CrCms\Foundation\Models;

use OutOfRangeException;
use Illuminate\Support\Facades\Config;

trait RelationMapTrait
{
    /**
     * @param string $name
     *
     * @return string
     */
    protected function relationByMap(string $name): string
    {
        $relation = Config::get("foundation.model_relation_mappings.{$name}");

        if (empty($relation)) {
            throw new OutOfRangeException("The relation {$name} unable to find mapping");
        }

        return $relation;
    }
}
