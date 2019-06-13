<?php

namespace CrCms\Foundation\Resources\Concerns;

trait WhenMakeConcern
{
    /**
     * @param mixed ...$parameters
     *
     * @return \Illuminate\Http\Resources\Json\JsonResource|null|array
     */
    public static function whenMake(...$parameters)
    {
        if (empty($parameters) || empty($parameters[0])) {
            return static::emptyReturn();
        }

        return static::make(...$parameters);
    }

    /**
     *
     * @return mixed
     */
    abstract protected static function emptyReturn();
}