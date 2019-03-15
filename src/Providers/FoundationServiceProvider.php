<?php

namespace CrCms\Foundation\Providers;

use Illuminate\Support\ServiceProvider;
use CrCms\Foundation\Commands\ModuleMakeCommand;

class FoundationServiceProvider extends ServiceProvider
{
    /**
     * @var bool
     */
    protected $defer = false;

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
            $this->basePath.'config/config.php' => $this->app->configPath("{$this->name}.php"),
        ]);
    }

    /**
     * register.
     *
     * @return void
     */
    public function register(): void
    {
        $configPath = $this->basePath.'config/config.php';
        if (file_exists($configPath)) {
            $this->mergeConfigFrom($configPath, $this->name);
        }

        $this->commands([ModuleMakeCommand::class]);

        if ($this->app['config']->get('foundation.auto_mount', false)) {
            $this->app->register(MountServiceProvider::class);
        }
    }
}
