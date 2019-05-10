<?php

namespace CrCms\Foundation\Tests\Commands;

use CrCms\Foundation\Commands\HandlerMakeCommand;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Application;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;

class HandlerMakeTest extends TestCase
{
    protected $input;

    protected $output;

    protected $app;

    protected function setUp(): void
    {
        // TODO: Change the autogenerated stub
        parent::setUp();
        $this->app = new Application(__DIR__.'/../');
        $this->app->useAppPath(__DIR__.'/../');
        $this->app = \Mockery::mock($this->app);
        $this->app->shouldReceive('getNamespace')->andReturn('Modules\\');


        $this->input = new ArgvInput();
        $this->output = new ConsoleOutput();
    }

    public function testMakeCommand()
    {
        $handler = new HandlerMakeCommand(new Filesystem());

        $handler->setLaravel($this->app);
        $handler->handle();
        dd($handler);

    }

}