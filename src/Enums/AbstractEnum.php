<?php

namespace CrCms\Foundation\Enums;

use MyCLabs\Enum\Enum;

class AbstractEnum extends Enum
{
    /**
     * Returns a value when called statically like so: MyEnum::SOME_VALUE() given SOME_VALUE is a class constant.
     *
     * @param string $name
     * @param array $arguments
     *
     * @return static|mixed
     * @throws \BadMethodCallException
     */
    public static function __callStatic($name, $arguments)
    {
        if (substr($name, -6) === '_VALUE') {
            $name = substr($name, 0, strlen($name) - 6);
            $func = 'getValue';
        } elseif (substr($name, -4) === '_KEY') {
            $name = substr($name, 0, strlen($name) - 4);
            $func = 'getKey';
        }

        $array = static::toArray();
        if (isset($array[$name]) || \array_key_exists($name, $array)) {
            if (isset($func)) {
                return call_user_func([new static($array[$name]), $func]);
            } else {
                return new static($array[$name]);
            }
        }

        throw new \BadMethodCallException("No static method or enum constant '$name' in class ".\get_called_class());
    }
}