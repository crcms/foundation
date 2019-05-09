<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-07-17 20:40
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Foundation\Handlers;

use CrCms\Foundation\Foundation\InstanceConcern;
use CrCms\Foundation\Handlers\Contracts\HandlerContract;

/**
 * Class AbstractHandler.
 */
abstract class AbstractHandler implements HandlerContract
{
    use InstanceConcern;
}
