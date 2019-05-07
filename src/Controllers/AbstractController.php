<?php

namespace CrCms\Foundation\Controllers;

use CrCms\Foundation\Helpers\InstanceConcern;
use CrCms\Foundation\Services\ResponseFactory;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use InstanceConcern;

    /**
     * @var
     */
    protected $repository;

    /**
     * @var ResponseFactory
     */
    protected $response;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $this->response = $this->response();
    }

    /**
     * @return ResponseFactory
     */
    protected function response(): ResponseFactory
    {
        return $this->app->make(ResponseFactory::class);
    }
}
