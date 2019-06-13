<?php

/**
 * @author simon <simon@crcms.cn>
 * @datetime 2018-11-12 20:06
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Foundation\Transporters;

use Illuminate\Http\Request;
use CrCms\Foundation\Framework;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Validation\ValidatesWhenResolved;
use CrCms\Foundation\Transporters\Contracts\DataProviderContract;

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

        $this->app->resolving(AbstractValidateDataProvider::class, function (AbstractValidateDataProvider $dataProvider, $app) {
            $dataProvider->setObject($this->resolveRequestData($app['request']));
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerAlias();

        $this->app->bind('data.provider', function ($app) {
            return new DataProvider($this->resolveRequestData($app['request']));
        });
    }

    /**
     * resolveRequestData.
     *
     * @param $request
     * @return array
     */
    protected function resolveRequestData($request): array
    {
        $params = $request->all();

        if ($request instanceof Request) {
            if (Framework::isLumen()) {
                $routeDetailParams = $request->route()[2] ?? [];
                $routeParams = array_merge($routeDetailParams, $request->route());
            } else {
                $routeParams = $request->route()->parameters();
            }
            $params = array_merge($params, $routeParams);
        }

        return $params;
    }

    /**
     * @return void
     */
    protected function registerAlias(): void
    {
        foreach ([
                     DataProviderContract::class,
                     AbstractDataProvider::class,
                 ] as $alias) {
            $this->app->alias('data.provider', $alias);
        }
    }
}
