<?php

namespace CrCms\Foundation\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class FunctionMakeCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:function';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new function';

    public function handle()
    {
        $appendOptions = [];
        if ($this->option('force')) {
            $appendOptions['--force'] = '--force';
        }

        $actions = array_intersect($this->getActions(), ['store', 'update', 'delete', 'list', 'show']);

        if (empty($actions)) {
            $this->error('Action not within the allowable range [store,update,delete,list,show]');

            return false;
        }

        $name = Str::studly($this->argument('name'));
        $basename = Str::studly(class_basename($name));
        $namespace = $this->getNamespace($name);

        $model = $namespace.'/Models/'.$basename.'Model';
        $repository = $namespace.'/Repositories/'.$basename.'Repository';
        $magic = $namespace.'/Repositories/Magic/'.$basename.'Magic';
        $controller = $namespace.'/Controllers/'.$basename.'Controller';
        $resource = $namespace.'/Resources/'.$basename.'Resource';
        $handlerPath = $namespace.'/Handlers/'.$basename.'/';
        $validationPath = $namespace.'/DataProviders/'.$basename.'/';

        if ($this->option('all')) {
            $this->input->setOption('model', true);
            $this->input->setOption('repository', true);
            $this->input->setOption('magic', true);
            $this->input->setOption('controller', true);
            $this->input->setOption('handler', true);
            $this->input->setOption('resource', true);
            $this->input->setOption('validation', true);
        }

        if ($this->option('model')) {
            $this->call('make:model', array_merge($appendOptions, ['name' => $model]));
        }

        if ($this->option('repository')) {
            $this->call('make:repository', array_merge($appendOptions, [
                'name' => $repository,
                '-m' => $model,
            ]));
        }

        if ($this->option('magic')) {
            $this->call('make:magic', array_merge($appendOptions, ['name' => $magic]));
        }

        if ($this->option('controller')) {
            $this->call('make:controller', [
                'name' => $controller,
                '--handler' => $handlerPath,
            ]); //array_merge($appendOptions, ['name' => $controller]));
        }

        if ($this->option('resource')) {
            $this->call('make:resource', ['name' => $resource]); //array_merge($appendOptions, ['name' => $resource]));
        }

        if ($this->option('handler')) {
            foreach ($actions as $action) {
                $this->call('make:handler', array_merge($appendOptions, [
                    'name' => $handlerPath.Str::studly($action).'Handler',
                    '-a' => $action,
                    '-m' => $model,
                    '-r' => $repository,
                    '--magic' => $magic,
                ]));
            }
        }

        if ($this->option('validation')) {
            foreach ($actions as $action) {
                $this->call('make:validation', array_merge($appendOptions, [
                    'name' => $validationPath.Str::studly($action).'DataProvider',
                ]));
            }
        }

        $this->info('Function '.$basename.' created successfully.');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the function'],
        ];
    }

    /**
     * @return array
     */
    protected function getActions(): array
    {
        $option = $this->option('action');

        return $option ? explode(',', $option) : ['store', 'update', 'delete', 'list', 'show'];
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
            ['all', null, InputOption::VALUE_NONE, 'Create all action'],
            ['action', 'a', InputOption::VALUE_OPTIONAL, 'Function action key:rule, Example: list,store,update,delete,show'],
            ['validation', null, InputOption::VALUE_NONE, 'Create validation'],
            ['model', 'm', InputOption::VALUE_NONE, 'Create model'],
            ['handler', null, InputOption::VALUE_NONE, 'Create handler'],
            ['controller', 'c', InputOption::VALUE_NONE, 'Create controller'],
            ['repository', 'r', InputOption::VALUE_NONE, 'Create repository'],
            ['resource', null, InputOption::VALUE_NONE, 'Create resource'],
            ['magic', null, InputOption::VALUE_NONE, 'Create repository magic'],
            ['force', null, InputOption::VALUE_NONE, 'Create the class even if the validation already exists'],
        ];
    }

    /**
     * Get the full namespace for a given class, without the class name.
     *
     * @param  string $name
     * @return string
     */
    protected function getNamespace(string $name): string
    {
        $name = str_replace('/', '\\', $name);

        return trim(implode('\\', array_slice(explode('\\', $name), 0, -1)), '\\');
    }
}
