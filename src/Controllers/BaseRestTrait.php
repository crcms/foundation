<?php

namespace CrCms\Foundation\Controllers;

use CrCms\Foundation\Logic\AbstractLogic;
use CrCms\Foundation\Exceptions\AppException;
use CrCms\Foundation\Resources\V2\JsonResource;
use CrCms\Foundation\Transporters\Contracts\DataProviderContract;

trait BaseRestTrait
{
    /**
     * @var array
     */
    //protected $scenes = [];

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $provider = $this->dataProvider();

        $provider->scenes(Arr::get($this->scenes, 'index.validate'))->validateResolved();

        try {
            $resources = $this->logic()->paginateForManagement($provider);
        } catch (AppException $e) {
            $this->response->errorBadRequest($e->getMessage());
        }

        return $this->response->paginator(
            $resources,
            $this->resource(),
            ['scene' => Arr::get($this->scenes, 'index.resource')]
        );
    }

    /**
     * @param $id
     *
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $provider = $this->dataProvider();
        $provider->scenes(Arr::get($this->scenes, 'show.validate'))->validateResolved();

        try {
            $resource = $this->logic()->show($provider, $id);
        } catch (AppException $e) {
            $this->response->errorBadRequest($e->getMessage());
        }

        return $this->response->resource(
            $resource,
            $this->resource(),
            ['scene' => Arr::get($this->scenes, 'show.resource')]
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
        } catch (AppException $e) {
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
        } catch (AppException $e) {
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
            $row = $this->logic()->destroy($provider, $id);
        } catch (AppException $e) {
            $this->response->errorBadRequest($e->getMessage());
        }

        return $this->response->noContent();
    }

    /**
     * @return AbstractLogic
     */
    abstract protected function logic();

    /**
     * @return ValidatesWhenResolved
     */
    abstract protected function dataProvider(): ValidatesWhenResolved;

    /**
     * @return string|JsonResource
     */
    abstract protected function resource();
}
