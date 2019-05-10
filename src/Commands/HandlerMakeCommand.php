<?php

namespace CrCms\Foundation\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;
use InvalidArgumentException;

class HandlerMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:handler';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new handler class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Handler';

    /**
     *
     * @return array
     */
    public function getOptions(): array
    {
        return [
            ['action', 'a', InputOption::VALUE_OPTIONAL, 'Create action type handler'],
            ['model', 'm', InputOption::VALUE_OPTIONAL, 'Create action type handler'],
            ['repository', 'r', InputOption::VALUE_OPTIONAL, 'Create action type handler'],
            ['magic', null, InputOption::VALUE_OPTIONAL, 'Create action type handler'],
            ['force', null, InputOption::VALUE_NONE, 'Create the class even if the handler already exists'],
        ];
    }

    /**
     * @param string $name
     *
     * @return string
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildClass($name): string
    {
        $replace = [
            'DummySegment' => Str::snake(
                basename(str_replace(class_basename($this->argument('name')), '', $this->argument('name')))
            ),
        ];

        if ($this->option('model')) {
            $modelClass = $this->parseType($this->option('model'));
            $replace['DummyFullModelClass'] = $modelClass;
            $replace['DummyModelClass'] = class_basename($modelClass);
            $replace['DummyModelVariable'] = lcfirst(class_basename($modelClass));
        }

        if ($this->option('repository')) {
            $repositoryClass = $this->parseType($this->option('repository'));
            $replace['DummyFullRepositoryClass'] = $repositoryClass;
            $replace['DummyRepositoryClass'] = class_basename($repositoryClass);
        }

        if ($this->option('magic')) {
            $magicClass = $this->parseType($this->option('magic'));
            $replace['DummyFullMagicClass'] = $magicClass;
            $replace['DummyMagicClass'] = class_basename($magicClass);
        }

        return str_replace(
            array_keys($replace), array_values($replace), parent::buildClass($name)
        );
    }

    /**
     * Get the fully-qualified model class name.
     *
     * @param  string $model
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    protected function parseType($type)
    {
        if (preg_match('([^A-Za-z0-9_/\\\\])', $type)) {
            throw new InvalidArgumentException("The type {$type} name contains invalid characters.");
        }

        $type = trim(str_replace('/', '\\', $type), '\\');

        if (! Str::startsWith($type, $rootNamespace = $this->laravel->getNamespace())) {
            $type = $rootNamespace.$type;
        }

        return $type;
    }

    /**
     *
     * @return string
     */
    protected function getStub(): string
    {
        $action = '';

        if ($this->option('action')) {
            $action = $this->option('action');
            if (! in_array($action, ['store', 'update', 'delete', 'list', 'show'])) {
                $this->error('Action not within the allowable range [store,update,delete,list]');
                exit(1);
            }
        }

        return __DIR__."/stubs/handler.{$action}.stub";
    }
}

