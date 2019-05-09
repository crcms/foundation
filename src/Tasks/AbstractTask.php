<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-07-17 20:40
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Foundation\Tasks;

use CrCms\Foundation\Foundation\InstanceTrait;
use CrCms\Foundation\Tasks\Contracts\TaskContract;

/**
 * Class AbstractTask.
 */
abstract class AbstractTask implements TaskContract
{
    use InstanceTrait;
}
