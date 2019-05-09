<?php

namespace CrCms\Foundation\Providers;

use CrCms\Foundation\Commands\ModuleMakeCommand;

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
     * register.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeDefaultConfig();

        $this->registerCommands();

        $this->loadServiceProvider();
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
}
