<?php

/**
 * @author simon <simon@crcms.cn>
 * @datetime 2018-08-12 13:55
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Foundation\Foundation\Concerns;

use Illuminate\Container\Container;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Contracts\Queue\Queue as Queue;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Support\Str;
use InvalidArgumentException;

/**
 * @property-read Container|Application $app
 * @property-read Config $config
 * @property-read Cache $cache
 * @property-read Queue $queue
 * @property-read EventDispatcher $event
 * @property-read AuthFactory $auth
 * @property-read Dispatcher $dispatcher
 * @property-read Guard $guard
 * @property-read \Illuminate\Contracts\Auth\Authenticatable|null $user
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
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function event(): EventDispatcher
    {
        return $this->app->make('events');
    }

    /**
     * @return Dispatcher
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
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
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function user()
    {
        return $this->guard->user();
    }

    /**
     * @return Queue
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function queue(): Queue
    {
        return $this->app->make(Queue::class);
    }

    /**
     * @param $abstract
     * @param array $parameters
     *
     * @return mixed
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function make($abstract, array $parameters = [])
    {
        return $this->app->make($abstract, $parameters);
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        $name = Str::camel($name);

        if (method_exists($this, Str::camel($name))) {
            return $this->{$name}();
        }

        throw new InvalidArgumentException("The property {$name} not allowed");
    }
}
