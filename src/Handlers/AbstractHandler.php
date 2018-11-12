<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-07-17 20:40
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Foundation\Handlers;

use CrCms\Foundation\Handlers\Contracts\HandlerContract;
use CrCms\Foundation\Helpers\InstanceConcern;

/**
 * Class AbstractHandler
 * @package CrCms\Foundation\Actions
 */
abstract class AbstractHandler implements HandlerContract
{
    use InstanceConcern;
}