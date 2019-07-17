<?php

namespace CrCms\Foundation;

class Framework
{
    /**
     * @return bool
     */
    public static function isLumen(): bool
    {
        return class_exists(\Laravel\Lumen\Application::class);
    }

    /**
     * @return bool
     */
    public static function isLaravel(): bool
    {
        return class_exists(\Illuminate\Foundation\Application::class);
    }
}
