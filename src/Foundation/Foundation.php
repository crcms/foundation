<?php

namespace CrCms\Foundation\Foundation;

use Illuminate\Contracts\Container\Container;

class Foundation
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @return bool
     */
    public function isLumen(): bool
    {
        return $this->container instanceof \Laravel\Lumen\Application;
    }

    /**
     * @return bool
     */
    public function isLaravel(): bool
    {
        return $this->container instanceof \Illuminate\Foundation\Application;
    }
}
