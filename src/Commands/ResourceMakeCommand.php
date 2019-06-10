<?php

namespace CrCms\Foundation\Commands;

use Illuminate\Foundation\Console\ResourceMakeCommand as BaseResourceMakeCommand;

class ResourceMakeCommand extends BaseResourceMakeCommand
{
    /**
     * @param string $rootNamespace
     *
     * @return string
     */
    public function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace;
    }

    /**
     * @return string
     */
    public function getStub(): string
    {
        return $this->collection()
            ? __DIR__.'/stubs/resource-collection.stub'
            : __DIR__.'/stubs/resource.stub';
    }
}
