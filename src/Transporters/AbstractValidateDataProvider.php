<?php

/**
 * @author simon <simon@crcms.cn>
 * @datetime 2018-11-14 22:35
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Foundation\Transporters;

use CrCms\Foundation\Transporters\Concerns\ValidateConcern;
use Illuminate\Contracts\Validation\ValidatesWhenResolved;

/**
 * Class AbstractValidateDataProvider
 * @package CrCms\Foundation\Transporters
 */
abstract class AbstractValidateDataProvider extends AbstractDataProvider implements ValidatesWhenResolved
{
    use ValidateConcern;
}