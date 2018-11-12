<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-07-17 20:40
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Foundation\Tasks;

use CrCms\Foundation\Helpers\InstanceConcern;
use CrCms\Foundation\Tasks\Contracts\TaskContract;

/**
 * Class AbstractTask
 * @package CrCms\Foundation\Tasks
 */
abstract class AbstractTask implements TaskContract
{
    use InstanceConcern;

}