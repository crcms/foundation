<?php

namespace CrCms\Foundation\Controllers;

use CrCms\Foundation\Logic\AbstractLogic;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

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
        try {
            $resources = $this->logic()->paginateForManagement();
        } catch (\Exception $e) {
            $this->response->errorBadRequest($e->getMessage());
        }

        return $this->response->paginator(
            $resources,
            SpikeResource::class,
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
        $provider->scenes($this->currentScene('store', 'validate'))->validateResolved();

        try {
            $resource = $this->logic()->store($provider);
        } catch (\Exception $e) {
            $this->response->errorBadRequest($e->getMessage());
        }

        return $this->response->resource(
            $resource,
            SpikeResource::class,
            $this->currentScene('store', 'resource')
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
        $provider->scenes($this->currentScene('update', 'validate'))->validateResolved();

        try {
            $resource = $this->logic()->update($provider, $id);
        } catch (\Exception $e) {
            $this->response->errorBadRequest($e->getMessage());
        }

        return $this->response->resource(
            $resource,
            SpikeResource::class,
            $this->currentScene('update', 'resource')
        );
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function destroy($id): Response
    {
        $provider = $this->dataProvider();
        $provider->scenes($this->currentScene('destroy', 'validate'))->validateResolved();

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
     * @param string $scene
     * @param string|null $type
     *
     * @return string
     */
    protected function currentScene(string $scene, ?string $type = null): string
    {
        if (! isset($this->scenes[$scene])) {
            throw new \OutOfRangeException("The scene [$scene] not found");
        }

        $types = $this->scenes[$scene];
        if (is_array($types) && isset($types[$type])) {
            return $types[$type];
        } else if (is_string($types)) {
            return $types;
        } else {
            throw new \OutOfRangeException("The scene type [$type] not found");
        }
    }
}