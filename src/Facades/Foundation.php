<?php

namespace CrCms\Foundation\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static bool isLumen()
 * @method static bool isLaravel()
 */
class Foundation extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return \CrCms\Foundation\Foundation\Foundation::class;
    }
}
