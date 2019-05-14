<?php

namespace CrCms\Foundation\Commands;

use Illuminate\Foundation\Console\RuleMakeCommand as BaseRuleMakeCommand;

class RuleMakeCommand extends BaseRuleMakeCommand
{
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
}