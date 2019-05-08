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
        $path = $this->basePath($this->basePath.$path);
        if (file_exists($path)) {
            $this->loadMigrationsFrom($path);
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
        $path = $this->basePath($path);
        if (file_exists($path)) {
            $this->loadTranslationsFrom($path, $this->name);
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
        $configPath = $this->basePath($path);
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
    protected function mapWebRoutes(string $path = 'routes/web.php'): void
    {
        $file = $this->basePath($path);

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
    protected function mapApiRoutes(string $path = 'routes/api.php'): void
    {
        $file = $this->basePath($path);

        if (file_exists($file)) {
            $this->loadRoutesFrom(
                Route::prefix('api')
                    ->middleware('api')
                    ->group($file));
        }
    }

    /**
     * @param string|null $path
     *
     * @return string
     */
    protected function basePath(?string $path = null): string
    {
        return $path ? rtrim($this->basePath, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.$path : $this->basePath;
    }
}