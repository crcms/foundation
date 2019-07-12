<?php

namespace CrCms\Foundation\Controllers;

use CrCms\Foundation\Logic\AbstractLogic;
use CrCms\Foundation\Resources\Resource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;

trait BaseRestTrait
{
    /**
     * @var array
     */
    protected $scenes = [];

    /**
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $provider = $this->dataProvider();
        $provider->scenes(Arr::get($this->scenes, 'index.validate'))->validateResolved();

        try {
            $resources = $this->logic()->paginateForManagement();
        } catch (\Exception $e) {
            $this->response->errorBadRequest($e->getMessage());
        }

        return $this->response->paginator(
            $resources,
            $this->resource(),
            ['scene' => Arr::get($this->scenes, 'index.resource')]
        );
    }

    /**
     * @param DataProviderContract $provider
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(): JsonResponse
    {
        $provider = $this->dataProvider();
        $provider->scenes(Arr::get($this->scenes, 'store.validate'))->validateResolved();

        try {
            $resource = $this->logic()->store($provider);
        } catch (\Exception $e) {
            $this->response->errorBadRequest($e->getMessage());
        }

        return $this->response->resource(
            $resource,
            $this->resource(),
            ['scene' => Arr::get($this->scenes, 'store.resource')]
        );
    }

    /**
     * @param DataProviderContract $provider
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id)
    {
        $provider = $this->dataProvider();
        $provider->scenes(Arr::get($this->scenes, 'update.validate'))->validateResolved();

        try {
            $resource = $this->logic()->update($provider, $id);
        } catch (\Exception $e) {
            $this->response->errorBadRequest($e->getMessage());
        }

        return $this->response->resource(
            $resource,
            $this->resource(),
            ['scene' => Arr::get($this->scenes, 'update.resource')]
        );
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function destroy($id): Response
    {
        $provider = $this->dataProvider();
        $provider->scenes(Arr::get($this->scenes, 'destroy.validate'))->validateResolved();

        try {
            $row = $this->logic()->destroy($id);
        } catch (\Exception $e) {
            $this->response->errorBadRequest($e->getMessage());
        }

        return $this->response->noContent();
    }

    /**
     *
     * @return AbstractLogic
     */
    abstract protected function logic();

    /**
     *
     * @return ValidatesWhenResolved
     */
    abstract protected function dataProvider(): ValidatesWhenResolved;

    /**
     *
     * @return string|Resource
     */
    abstract protected function resource();
}