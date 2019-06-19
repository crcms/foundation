<?php

namespace CrCms\Foundation\Resources\V2\Concerns;

trait TypeConcern
{
    /**
     * @var array
     */
    protected $casts = [];

//    /**
//     * @var bool
//     */
//    protected $numericCastString = true;
//
//    /**
//     * @return bool
//     */
//    public function getNumericCastString(): bool
//    {
//        return $this->numericCastString;
//    }
//
//    /**
//     * @param bool $numericCastString
//     *
//     * @return TypeConcern
//     */
//    public function setNumericCastString(bool $numericCastString): self
//    {
//        $this->numericCastString = $numericCastString;
//
//        return $this;
//    }

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
//        if ($this->numericCastString && is_numeric($value)) {
//            return strval($value);
//        }

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
                return (array) $value;
            case 'boolean':
            case 'bool':
                return boolval($value);
            default:
                return strval($value);
        }
    }
}