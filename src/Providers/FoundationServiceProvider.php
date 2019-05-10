<?php

namespace CrCms\Foundation\Providers;

use CrCms\Foundation\Commands\ControllerMakeCommand;
use CrCms\Foundation\Commands\FunctionMakeCommand;
use CrCms\Foundation\Commands\HandlerMakeCommand;
use CrCms\Foundation\Commands\ModuleMakeCommand;
use CrCms\Foundation\Commands\ResourceMakeCommand;
use CrCms\Foundation\Commands\TaskMakeCommand;
use CrCms\Foundation\Commands\ValidationMakeCommand;
use Illuminate\Database\Schema\Blueprint;
use CrCms\Foundation\Schemas\Blueprint as CrCmsBlueprint;

class FoundationServiceProvider extends AbstractModuleServiceProvider
{
    /**
     * @var string
     */
    protected $basePath = __DIR__.'/../../';

    /**
     * @var string
     */
    protected $name = 'foundation';

    /**
     * boot.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->publishes([
            $this->basePath('config/config.php') => $this->app->configPath("{$this->name}.php"),
        ]);

        $this->extendCommands();
    }

    /**
     *
     * @return void
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \ReflectionException
     */
    public function register(): void
    {
        $this->mergeDefaultConfig();

        $this->registerCommands();

        $this->loadServiceProvider();

        $this->loadBlueprint();
    }

    /**
     *
     * @return void
     */
    protected function extendCommands(): void
    {
        $this->app->extend('command.resource.make', function ($resource, $app) {
            return new ResourceMakeCommand($app['files']);
        });

        $this->app->extend('command.controller.make', function ($controller, $app) {
            return new ControllerMakeCommand($app['files']);
        });
    }

    /**
     *
     * @return void
     */
    protected function loadServiceProvider(): void
    {
        if ($this->app['config']->get('foundation.load', false)) {
            $this->app->register(LoadServiceProvider::class);
        }
    }

    /**
     *
     * @return void
     */
    protected function registerCommands(): void
    {
        $this->commands([
            ModuleMakeCommand::class,
            HandlerMakeCommand::class,
            TaskMakeCommand::class,
            ValidationMakeCommand::class,
            FunctionMakeCommand::class,
        ]);
    }

    /**
     *
     * @return void
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \ReflectionException
     */
    protected function loadBlueprint(): void
    {
        Blueprint::macro('integerTimestamps', function () {
            CrCmsBlueprint::integerTimestamps($this);
        });
        Blueprint::macro('integerUids', function () {
            CrCmsBlueprint::integerUids($this);
        });
        Blueprint::macro('integerSoftDeletes', function () {
            CrCmsBlueprint::integerSoftDeletes($this);
        });
        Blueprint::macro('integerSoftDeleteUid', function () {
            CrCmsBlueprint::integerSoftDeleteUid($this);
        });
        Blueprint::macro('unsignedBigIntegerDefault', function (...$args) {
            return CrCmsBlueprint::unsignedBigIntegerDefault($this, ...$args);
        });
        Blueprint::macro('unsignedTinyIntegerDefault', function (...$args) {
            return CrCmsBlueprint::unsignedTinyIntegerDefault($this, ...$args);
        });
        Blueprint::macro('unsignedIntegerDefault', function (...$args) {
            return CrCmsBlueprint::unsignedIntegerDefault($this, ...$args);
        });
    }
}
