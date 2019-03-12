<?php

namespace CrCms\Foundation\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class ModuleMakeCommand
 * @package CrCms\Microservice\Console\Commands
 */
class ModuleMakeCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:module';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new module';

    /**
     * @var Filesystem
     */
    protected $files;

    /**
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();
        $this->files = $filesystem;
    }

    /**
     * @return void
     */
    public function handle(): void
    {
        $name = Str::ucfirst($this->argument('name'));

        $this->createModules($name);
        $this->createRoutes($name);
        $this->createDatabase($name);
        $this->createConfigFile($name);

        $this->info("The module:{$name} create success");
    }

    /**
     * @param string $name
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @return void
     */
    protected function createModules(string $name): void
    {
        $this->autoCreateDirs([
            base_path($this->moduleName().'/'.$name.'/Schedules'),
            base_path($this->moduleName().'/'.$name.'/Config'),
            base_path($this->moduleName().'/'.$name.'/Commands'),
            base_path($this->moduleName().'/'.$name.'/Events'),
            base_path($this->moduleName().'/'.$name.'/Exceptions'),
            base_path($this->moduleName().'/'.$name.'/Handlers'),
            base_path($this->moduleName().'/'.$name.'/Tasks'),
            base_path($this->moduleName().'/'.$name.'/Jobs'),
            base_path($this->moduleName().'/'.$name.'/Listeners'),
            base_path($this->moduleName().'/'.$name.'/Models'),
            base_path($this->moduleName().'/'.$name.'/Providers'),
            base_path($this->moduleName().'/'.$name.'/Repositories/Constants'),
            base_path($this->moduleName().'/'.$name.'/Middleware'),
            base_path($this->moduleName().'/'.$name.'/DataProviders'),
            base_path($this->moduleName().'/'.$name.'/Controllers'),
            base_path($this->moduleName().'/'.$name.'/Resources'),
            base_path($this->moduleName().'/'.$name.'/Routes'),
            base_path($this->moduleName().'/'.$name.'/Translations'),
            base_path($this->moduleName().'/'.$name.'/Database/Factories'),
            base_path($this->moduleName().'/'.$name.'/Database/Migrations'),
            base_path($this->moduleName().'/'.$name.'/Database/Seeds'),
        ]);
    }

    /**
     * autoCreateDirs
     *
     * @param array $dirs
     * @return void
     */
    protected function autoCreateDirs(array $dirs): void
    {
        foreach ($dirs as $dir) {
            if (!$this->files->exists($dir) && !empty($dir)) {
                $this->files->makeDirectory($dir, 0755, true);
            }
        }
    }

    /**
     * Create module config
     *
     * @param string $name
     * @return void
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function createConfigFile(string $name): void
    {
        $file = base_path($this->moduleName().'/'.$name.'/Config/config.php');
        if (!$this->files->exists($file)) {
            $this->files->put($file, $this->files->get(__DIR__.'/stubs/config.stub'));
        }
    }

    /**
     * @param string $name
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function createRoutes(string $name): void
    {
        $apiFile = base_path($this->moduleName().'/'.$name.'/Routes/api.php');
        if (!$this->files->exists($apiFile)) {
            $this->files->put($apiFile, $this->files->get(__DIR__.'/stubs/routes/api.stub'));
        }

        $webFile = base_path($this->moduleName().'/'.$name.'/Routes/web.php');
        if (!$this->files->exists($webFile)) {
            $this->files->put($webFile, $this->files->get(__DIR__.'/stubs/routes/web.stub'));
        }
    }

    /**
     * @param string $name
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @return void
     */
    protected function createDatabase(string $name): void
    {
        $this->files->put($this->moduleName().'/'.$name.'/Database/Factories/UserFactory.php', $this->files->get(__DIR__ . '/stubs/factory.stub'));
        $this->files->put($this->moduleName().'/'.$name.'/Database/Migrations/2014_10_12_000000_test_table.php', $this->files->get(__DIR__ . '/stubs/migration.stub'));
        $this->files->put($this->moduleName().'/'.$name.'/Database/Seeds/DatabaseSeeder.php', $this->files->get(__DIR__ . '/stubs/seed.stub'));
    }

    /**
     * moduleName
     *
     * @return string
     */
    protected function moduleName(): string
    {
        return basename($this->laravel['config']->get('foundation.module_path'));
    }

    /**
     * @return array
     */
    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The module name'],
        ];
    }
}
