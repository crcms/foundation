<?php

namespace CrCms\Foundation\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class TaskMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:task';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new task class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Task';

    /**
     *
     * @return array
     */
    public function getOptions(): array
    {
        return [
            ['force', null, InputOption::VALUE_NONE, 'Create the class even if the task already exists'],
        ];
    }

    /**
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/task.stub';
    }
}

