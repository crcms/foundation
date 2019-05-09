<?php

namespace CrCms\Foundation\Models;

trait MacroableTrait
{
    use \Illuminate\Support\Traits\Macroable {
        __call as macroCall;
    }

    /**
     * @param $key
     *
     * @return mixed
     */
    public function getRelationValue($key)
    {
        $relation = parent::getRelationValue($key);
        if (! $relation && static::hasMacro($key)) {
            return $this->getRelationshipFromMethod($key);
        }

        return $relation;
    }

    /**
     * @param $method
     * @param $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (static::hasMacro($method)) {
            return $this->macroCall($method, $parameters);
        }

        return parent::__call($method, $parameters);
    }

    /**
     * @param $method
     * @param $parameters
     *
     * @return mixed
     */
    public static function __callStatic($method, $parameters)
    {
        return parent::__callStatic($method, $parameters);
    }
}