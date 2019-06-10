<?php

namespace CrCms\Foundation\Commands;

use Symfony\Component\Console\Input\InputOption;
use Illuminate\Routing\Console\ControllerMakeCommand as BaseControllerMakeCommand;

class ControllerMakeCommand extends BaseControllerMakeCommand
{
    /**
     * @return string
     */
    public function getStub(): string
    {
        return __DIR__.'/stubs/controller.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace;
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function buildClass($name): string
    {
        $handler = $this->option('handler');
        if ($handler) {
            $handlerNamespace = $this->getNamespace($this->qualifyClass($handler)).'';
            $resourceNamespace = $this->getNamespace($this->getNamespace($handlerNamespace)).'\Resources\\'.basename($handler).'Resource';
            $resourceClass = class_basename($resourceNamespace);

            return str_replace(
                ['DummyHandlerNamespace', 'DummyResourceNamespace', 'DummyResourceClass'],
                [$handlerNamespace, $resourceNamespace, $resourceClass],
                parent::buildClass($name)
            );
        }

        return parent::buildClass($name);
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        $options = parent::getOptions();
        $options[] = [
            'handler', null, InputOption::VALUE_OPTIONAL, 'Generate a resource controller for the given handler.',
        ];

        return $options;
    }
}
