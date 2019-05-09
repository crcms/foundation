<?php

namespace CrCms\Foundation\Providers;

use CrCms\Foundation\Commands\ModuleMakeCommand;
use Illuminate\Database\Schema\Blueprint;

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
    protected function loadServiceProvider(): void
    {
        if ($this->app['config']->get('foundation.mount', false)) {
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
        Blueprint::mixin($this->app->make(\CrCms\Foundation\Schemas\Blueprint::class));
    }
}
