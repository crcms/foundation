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
}