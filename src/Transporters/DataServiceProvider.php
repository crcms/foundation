<?php

/**
 * @author simon <simon@crcms.cn>
 * @datetime 2018-11-12 20:06
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Foundation\Transporters;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Validation\ValidatesWhenResolved;
use CrCms\Foundation\Transporters\Contracts\DataProviderContract;

/**
 * Class DataServiceProvider.
 */
class DataServiceProvider extends ServiceProvider
{
    /**
     * @var bool
     */
    protected $defer = true;

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
        $this->registerAlias();

        $this->app->bind('data.provider', function ($app) {
            return new DataProvider($app['request']->all());
        });
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

    /**
     * @return array
     */
    public function provides(): array
    {
        return [
            'data.provider',
            DataProvider::class,
            DataProviderContract::class,
            AbstractDataProvider::class,
            AbstractValidateDataProvider::class
        ];
    }
}
