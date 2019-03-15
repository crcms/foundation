<?php

/**
 * @author simon <simon@crcms.cn>
 * @datetime 2018-11-14 22:35
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Foundation\Transporters;

use Illuminate\Contracts\Validation\ValidatesWhenResolved;
use CrCms\Foundation\Transporters\Concerns\ValidateConcern;

/**
 * Class AbstractValidateDataProvider.
 */
abstract class AbstractValidateDataProvider extends AbstractDataProvider implements ValidatesWhenResolved
{
    use ValidateConcern;
}
