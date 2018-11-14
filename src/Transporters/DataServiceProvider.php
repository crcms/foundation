<?php

/**
 * @author simon <simon@crcms.cn>
 * @datetime 2018-11-12 20:06
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Foundation\Transporters;

use CrCms\Foundation\Transporters\Contracts\DataProviderContract;
use Illuminate\Contracts\Validation\ValidatesWhenResolved;
use Illuminate\Support\ServiceProvider;

/**
 * Class DataServiceProvider
 * @package CrCms\Microservice\Transporters
 */
class DataServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->afterResolving(ValidatesWhenResolved::class, function ($resolved) {
            $resolved->validateResolved();
        });

        $this->app->resolving(DataProviderContract::class, function (DataProviderContract $dataProvider, $app) {
            $dataProvider->setObject($app['request']->all());
        });
    }


    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }
}