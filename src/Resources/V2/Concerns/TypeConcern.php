<?php

namespace CrCms\Foundation\Resources\V2\Concerns;

use Illuminate\Support\Carbon;

trait TypeConcern
{
    /**
     * @var array
     */
    protected $casts = [];

    /**
     * @param $value
     * @param string $field
     *
     * @return mixed
     */
    protected function conversion($value, string $field)
    {
        if (isset($this->casts[$field])) {
            return $this->cast($this->casts[$field], $value);
        } elseif (isset($this->casts['*'])) {
            return $this->cast($this->casts['*'], $value);
        } else {
            return $value;
        }
    }

    /**
     * @param string $type
     * @param $value
     *
     * @return array|bool|float|int|string
     */
    protected function cast(string $type, $value)
    {
        if (stripos($type, 'date') !== false) {
            $types = explode(':', $type);
            $format = isset($types[1]) ? $types[1] : 'Y-m-d H:i:s';
            if ($value instanceof Carbon) {
                return $value->rawFormat($format);
            } elseif ($value instanceof \DateTimeInterface) {
                return $value->format($format);
            } elseif (is_numeric($value)) {
                return date($format, $value);
            } else {
                return $value;
            }
        }

        switch ($type) {
            case 'int':
            case 'integer':
                return intval($value);
            case 'str':
            case 'string':
                return strval($value);
            case 'float':
            case 'double':
                return floatval($value);
            case 'json':
            case 'array':
                return (array)$value;
            case 'boolean':
            case 'bool':
                return boolval($value);
            default:
                return $value;
        }
    }
}
