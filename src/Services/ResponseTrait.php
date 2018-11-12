<?php

/**
 * @author simon <simon@crcms.cn>
 * @datetime 2018-11-12 20:02
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Foundation\Services;

/**
 * Class ResponseTrait
 * @package CrCms\Foundation\Services
 */
trait ResponseTrait
{
    /**
     * @return ResponseFactory
     */
    protected function response(): ResponseFactory
    {
        return app(ResponseFactory::class);
    }
}