<?php

namespace CrCms\Foundation\Providers;

use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

abstract class AbstractModuleServiceProvider extends ServiceProvider
{
    /**
     * @var string
     */
    protected $basePath = __DIR__.'/../../';

    /**
     * @var string
     */
    protected $name;

    /**
     * Default model relation mappings.
     *
     * @var array
     */
    protected $relationMappings = [];

    /**
     * loadDefaultMigrations.
     *
     * @param string $path
     * @return void
     */
    protected function loadDefaultMigrations(string $path = 'database/migrations'): void
    {
        $path = $this->basePath($path);
        if (file_exists($path)) {
            $this->loadMigrationsFrom($path);
        }
    }

    /**
     * loadDefaultTranslations.
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
     * @param string $path
     *
     * @return void
     */
    protected function loadDefaultRoutes(string $path = 'routes/route.php'): void
    {
        $file = $this->basePath($path);

        if (file_exists($file)) {
            $this->loadRoutesFrom($file);
        }
    }

    /**
     * @param string $path
     *
     * @return void
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function mergeDefaultConfig(string $path = 'config/config.php'): void
    {
        if ($this->isLumen()) {
            $this->app->configure($this->name);
        }

        $configPath = $this->basePath($path);
        if (file_exists($configPath)) {
            $this->mergeConfigFrom($configPath, $this->name);
        }
    }

    /**
     * Merge relation mappings.
     *
     * @return void
     */
    protected function loadRelationMapping(): void
    {
        $allMappings = $this->app['config']->get('foundation.model_relation_mappings', []);

        $relationMappings = array_merge(
            $this->relationMappings,
            Arr::only($allMappings, array_keys($this->relationMappings))
        );

        $this->app['config']->set('foundation.model_relation_mappings', array_merge($allMappings, $relationMappings));

        Relation::morphMap($relationMappings);
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

    /**
     * @return bool
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function isLumen(): bool
    {
        return $this->app->make('foundation')->isLumen();
    }
}
