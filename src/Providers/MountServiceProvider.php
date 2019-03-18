<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2019-01-19 00:06
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2019 Rights Reserved CRCMS
 */

namespace CrCms\Foundation\Providers;

use Illuminate\Support\Str;
use Symfony\Component\Finder\Finder;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Finder\SplFileInfo;
use Illuminate\Console\Scheduling\Schedule;

/**
 * Class MountServiceProvider.
 */
class MountServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot(): void
    {
        if (is_dir($this->modulePath())) {
            $this->scanLoadMigrations();
            $this->scanLoadTranslations();
            $this->scanLoadRoutes();
            $this->scanCommands();
            $this->scanLoadSchedules();
        }
    }

    /**
     * @return void
     */
    public function register(): void
    {
        if (is_dir($this->modulePath())) {
            $this->scanLoadConfig();
        }
    }

    /**
     * Merge config.
     *
     * @return void
     */
    protected function scanLoadConfig(): void
    {
        /* @var SplFileInfo $directory */
        /* @var SplFileInfo $file */
        foreach (Finder::create()->directories()->name('Config')->in($this->modulePath()) as $directory) {
            foreach (Finder::create()->files()->name('*.php')->in($directory->getPathname()) as $file) {
                $this->mergeConfigFrom($file->getPathname(), Str::snake($directory->getRelativePath()));
            }
        }
    }

    /**
     * @return void
     */
    protected function scanLoadMigrations(): void
    {
        /* @var SplFileInfo $directory */
        foreach (Finder::create()->directories()->name('Migrations')->in($this->modulePath()) as $directory) {
            $this->loadMigrationsFrom($directory->getPathname());
        }
    }

    /**
     * @return void
     */
    protected function scanLoadTranslations(): void
    {
        /* @var SplFileInfo $directory */
        foreach (Finder::create()->directories()->name('Translations')->in($this->modulePath()) as $directory) {
            $this->loadTranslationsFrom($directory->getPathname(), Str::snake($directory->getRelativePath()));
        }
    }

    /**
     * @return void
     */
    protected function scanLoadRoutes(): void
    {
        /* @var SplFileInfo $directory */
        /* @var SplFileInfo $file */
        foreach (Finder::create()->directories()->name('Routes')->in($this->modulePath()) as $directory) {
            foreach (Finder::create()->files()->name('*.php')->in($directory->getPathname()) as $file) {
                require $file->getPathname();
            }
        }
    }

    /**
     * @return void
     */
    protected function scanCommands(): void
    {
        /* @var SplFileInfo $file */
        foreach (Finder::create()->files()->name('*Command.php')->in($this->modulePath()) as $file) {
            $class = $this->fileToClass($file);
            if ($class && ! in_array($class, $this->app['config']->get('mount.commands', []))) {
                $classReflection = new \ReflectionClass($class);
                if (!$classReflection->isAbstract()) {
                    $this->commands($class);
                }
            }
        }
    }

    /**
     * @return void
     */
    protected function scanLoadSchedules()
    {
        /* @var SplFileInfo $file */
        $schedule = $this->app->make(Schedule::class);

        foreach (Finder::create()->files()->name('*Schedule.php')->in($this->modulePath()) as $file) {
            $class = $this->fileToClass($file);
            if ($class && ! in_array($class, $this->app['config']->get('mount.schedules', []), true)) {
                $this->app->make($class)->handle($schedule);
            }
        }
    }

    /**
     * @param SplFileInfo $file
     *
     * @return string|null
     */
    protected function fileToClass(SplFileInfo $file)
    {
        preg_match('/.*namespace\s([^\;]+)?/i', $file->getContents(), $match);

        if (isset($match[1])) {
            $class = $match[1].'\\'.$file->getBasename('.php');
            if (class_exists($class)) {
                return $class;
            }
        }
    }

    /**
     * modulePath.
     *
     * @return string
     */
    protected function modulePath(): string
    {
        return $this->app['config']->get('foundation.module_path');
    }
}
