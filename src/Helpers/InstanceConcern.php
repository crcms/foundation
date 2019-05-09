<?php

/**
 * @author simon <simon@crcms.cn>
 * @datetime 2018-08-12 13:55
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Foundation\Handlers;

use CrCms\Foundation\Foundation\InstanceTrait;
use Illuminate\Container\Container;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Contracts\Queue\Queue as Queue;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Foundation\Application;

/**
 * @property-read Container|Application $app
 * @property-read Config $config
 * @property-read Cache $cache
 * @property-read Queue $queue
 * @property-read EventDispatcher $event
 * @property-read AuthFactory $auth
 * @property-read Dispatcher $dispatcher
 * @property-read Guard $guard
 */
trait InstanceConcern
{
    use InstanceTrait;
}
