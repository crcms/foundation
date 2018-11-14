<?php

namespace CrCms\Foundation;

use Illuminate\Support\ServiceProvider;
use CrCms\Microservice\Foundation\Application as CrCmsMicroSerivceApplication;

/**
 * Class ModuleServiceProvider
 * @package CrCms\Framework\App\Providers
 */
abstract class ModuleServiceProvider extends ServiceProvider
{
    /**
     * @var string
     */
    protected $basePath;

    /**
     * @var string
     */
    protected $name;

    /**
     * @return void
     */
    public function boot(): void
    {
        $this->map();

        if (file_exists($this->basePath . 'database/migrations')) {
            $this->loadMigrationsFrom($this->basePath . 'database/migrations');
        }

        if (file_exists($this->basePath . 'resources/lang')) {
            $this->loadTranslationsFrom($this->basePath . 'resources/lang', $this->name);
        }
    }

    /**
     * @return void
     */
    protected function map(): void
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();
    }

    /**
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            $this->basePath . "config/config.php", $this->name
        );
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes(): void
    {
        $file = $this->basePath . 'routes/web.php';

        if (file_exists($file)) {
            $this->loadRoutesFrom($file);
        }
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes(): void
    {
        $file = $this->basePath . 'routes/api.php';

        if (file_exists($file)) {
            $this->loadRoutesFrom(
            /*Route::prefix('api')
                ->middleware('api')
                ->group($file)*/
                $file
            );
        }
    }

    /**
     * @return bool
     */
    protected function isRunningInMicroservice(): bool
    {
        return $this->app instanceof CrCmsMicroSerivceApplication;
    }
}