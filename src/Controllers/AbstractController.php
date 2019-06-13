<?php

namespace CrCms\Foundation\Controllers;

use CrCms\Foundation\Foundation\InstanceTrait;
use CrCms\Foundation\Response\Factory as ResponseFactory;

if (class_exists(\Laravel\Lumen\Routing\Controller::class)) {
    abstract class AbstractController extends \Laravel\Lumen\Routing\Controller
    {
        use InstanceTrait;

        /**
         * @var
         */
        protected $repository;

        /**
         * @var ResponseFactory
         */
        protected $response;

        /**
         * @throws \Illuminate\Contracts\Container\BindingResolutionException
         */
        public function __construct()
        {
            $this->response = $this->response();
        }

        /**
         * @return ResponseFactory
         *
         * @throws \Illuminate\Contracts\Container\BindingResolutionException
         */
        protected function response(): ResponseFactory
        {
            return $this->make(ResponseFactory::class)->setFactory(new \Laravel\Lumen\Http\ResponseFactory());
        }
    }
} else {
    abstract class AbstractController extends \Illuminate\Routing\Controller
    {
        use InstanceTrait;

        /**
         * @var
         */
        protected $repository;

        /**
         * @var ResponseFactory
         */
        protected $response;

        /**
         * @throws \Illuminate\Contracts\Container\BindingResolutionException
         */
        public function __construct()
        {
            $this->response = $this->response();
        }

        /**
         * @return ResponseFactory
         *
         * @throws \Illuminate\Contracts\Container\BindingResolutionException
         */
        protected function response(): ResponseFactory
        {
            return $this->make(ResponseFactory::class)->setFactory($this->make(\Illuminate\Contracts\Routing\ResponseFactory::class));
        }
    }
}
