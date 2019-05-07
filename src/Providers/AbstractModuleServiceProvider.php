<?php

namespace CrCms\Foundation\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

abstract class AbstractModuleServiceProvider extends ServiceProvider
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

        $this->loadDefaultMigrations();

        $this->loadDefaultTranslations();
    }

    /**
     * @return void
     */
    public function register(): void
    {
        $this->mergeDefaultConfig();
    }

    /**
     * loadDefaultMigrations
     *
     * @param string $path
     * @return void
     */
    protected function loadDefaultMigrations(string $path = 'database/migrations'): void
    {
        if (file_exists($this->basePath.$path)) {
            $this->loadMigrationsFrom($this->basePath.$path);
        }
    }

    /**
     * loadDefaultTranslations
     *
     * @param string $path
     * @return void
     */
    protected function loadDefaultTranslations(string $path = 'resources/lang'): void
    {
        if (file_exists($this->basePath.$path)) {
            $this->loadTranslationsFrom($this->basePath.$path, $this->name);
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
     * mergeDefaultConfig
     *
     * @param string $path
     * @return void
     */
    protected function mergeDefaultConfig(string $path = 'config/config.php'): void
    {
        $configPath = $this->basePath.$path;
        if (file_exists($configPath)) {
            $this->mergeConfigFrom($configPath, $this->name);
        }
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
        $file = $this->basePath.'routes/web.php';

        if (file_exists($file)) {
            $this->loadRoutesFrom(
                Route::middleware('web')
                    ->group($file));
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
        $file = $this->basePath.'routes/api.php';

        if (file_exists($file)) {
            $this->loadRoutesFrom(
                Route::prefix('api')
                    ->middleware('api')
                    ->group($file));
        }
    }
}