<?php

/**
 * @author simon <simon@crcms.cn>
 * @datetime 2018-08-12 13:55
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Foundation\Helpers;

use CrCms\Microservice\Foundation\Application;
use Illuminate\Container\Container;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Str;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Events\Dispatcher as EventDispatcher;
use InvalidArgumentException;

/**
 * @property-read Container|Application $app
 * @property-read Config $config
 * @property-read Cache $cache
 * @property-read EventDispatcher $event
 * @property-read AuthFactory $auth
 * @property-read Dispatcher $dispatcher
 * @property-read Guard $guard
 *
 * Trait InstanceConcern
 * @package CrCms\Foundation\Helpers
 */
trait InstanceConcern
{
    /**
     * @return Container|Application
     */
    public function app(): Container
    {
        return Container::getInstance();
    }

    /**
     * @return Config
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function config(): Config
    {
        return $this->app->make(Config::class);
    }

    /**
     * @return Cache
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function cache(): Cache
    {
        return $this->app->make(Cache::class);
    }

    /**
     * @return AuthFactory
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function auth(): AuthFactory
    {
        return $this->app->make(AuthFactory::class);
    }

    /**
     * @return EventDispatcher
     */
    public function event(): EventDispatcher
    {
        return $this->app->make('events');
    }

    /**
     * @return Dispatcher
     */
    public function dispatcher(): Dispatcher
    {
        return $this->app->make(Dispatcher::class);
    }

    /**
     * @param string|null $guard
     * @return Guard
     */
    public function guard($guard = null): Guard
    {
        $guard = is_null($guard) ? $this->config->get('auth.defaults.guard') : $guard;

        return $this->auth->guard($guard);
    }

    /**
     * @param $name
     * @return null
     */
    public function __get($name)
    {
        $name = Str::camel($name);

        if (method_exists($this, Str::camel($name))) {
            return $this->{$name}();
        }

        return null;
    }
}