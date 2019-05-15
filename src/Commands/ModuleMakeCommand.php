<?php

namespace CrCms\Foundation\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class ModuleMakeCommand.
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
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
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
            $this->modulePath($name.'/Schedules'),
            $this->modulePath($name.'/Config'),
            $this->modulePath($name.'/Commands'),
            $this->modulePath($name.'/Events'),
            $this->modulePath($name.'/Exceptions'),
            $this->modulePath($name.'/Handlers'),
            $this->modulePath($name.'/Tasks'),
            $this->modulePath($name.'/Jobs'),
            $this->modulePath($name.'/Listeners'),
            $this->modulePath($name.'/Models'),
            $this->modulePath($name.'/Providers'),
            $this->modulePath($name.'/Repositories'),
            $this->modulePath($name.'/Middleware'),
            $this->modulePath($name.'/DataProviders'),
            $this->modulePath($name.'/Controllers'),
            $this->modulePath($name.'/Resources'),
            $this->modulePath($name.'/Routes'),
            $this->modulePath($name.'/Translations'),
            $this->modulePath($name.'/Database/Factories'),
            $this->modulePath($name.'/Database/Migrations'),
            $this->modulePath($name.'/Database/Seeds'),
        ]);
    }

    /**
     * autoCreateDirs.
     *
     * @param array $dirs
     * @return void
     */
    protected function autoCreateDirs(array $dirs): void
    {
        foreach ($dirs as $dir) {
            if (! $this->files->exists($dir) && ! empty($dir)) {
                $this->files->makeDirectory($dir, 0755, true);
            }
        }
    }

    /**
     * Create module config.
     *
     * @param string $name
     * @return void
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function createConfigFile(string $name): void
    {
        $file = $this->modulePath($name.'/Config/config.php');
        if (! $this->files->exists($file)) {
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
        $apiFile = $this->modulePath($name.'/Routes/route.php');
        if (! $this->files->exists($apiFile)) {
            $this->files->put($apiFile, $this->files->get(__DIR__.'/stubs/routes/route.stub'));
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
        $this->files->put($this->modulePath($name.'/Database/Factories/UserFactory.php'), $this->files->get(__DIR__.'/stubs/factory.stub'));
        $this->files->put($this->modulePath($name.'/Database/Migrations/2014_10_12_000000_test_table.php'), $this->files->get(__DIR__.'/stubs/migration.stub'));
        $this->files->put($this->modulePath($name.'/Database/Seeds/DatabaseSeeder.php'), $this->files->get(__DIR__.'/stubs/seed.stub'));
    }

    /**
     * moduleName.
     *
     * @return string
     */
    protected function moduleName(): string
    {
        return basename($this->laravel['config']->get('foundation.module_path'));
    }

    /**
     * @param string $path
     *
     * @return string
     */
    protected function modulePath(string $path = '')
    {
        return $this->basePath($this->moduleName().($path ? DIRECTORY_SEPARATOR.$path : $path));
    }

    /**
     * @param string $path
     *
     * @return string
     */
    protected function basePath(string $path = ''): string
    {
        return $this->getLaravel()->basePath().($path ? DIRECTORY_SEPARATOR.$path : $path);
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
