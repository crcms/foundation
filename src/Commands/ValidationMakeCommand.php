<?php

namespace CrCms\Foundation\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;
use function CrCms\Foundation\Helpers\var_export;

class ValidationMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:validation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new validation class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Validation';

    /**
     * @return bool|null
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        if (parent::handle() === false && ! $this->option('force')) {
            return false;
        }

        if ($this->option('make-rule')) {
            $this->call('make:rule', ['name' => str_replace('Rules/', '', $this->option('make-rule'))]);
        }
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
            ['rule', 'r', InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'Validate rules, Formatting: key:rule, Example: name:required|max:10'],
            ['include-rule', 'i', InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'Include rule namespace, Example: -i Rules/In -i Rules/Out '],
            ['make-rule', 'm', InputOption::VALUE_OPTIONAL, 'Make rule namespace'],
            ['force', null, InputOption::VALUE_NONE, 'Create the class even if the validation already exists'],
        ];
    }

    /**
     * @param string $name
     *
     * @return mixed|string
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function buildClass($name)
    {
        return str_replace(['DummyRuleNamespace', 'DummyRule'], [$this->parseRuleNamespace(), $this->parseRule()], parent::buildClass($name));
    }

    /**
     * @return string
     */
    protected function parseRule(): string
    {
        $rules = [];

        if ($this->option('rule')) {
            foreach ($this->option('rule') as $item) {
                $rules[substr($item, 0, strpos($item, ':'))] = explode('|', substr($item, strpos($item, ':') + 1));
            }

            return var_export($rules, true);
        }

        return '[]';
    }

    /**
     * @return string
     */
    protected function parseRuleNamespace(): string
    {
        $ruleNamespace = [];

        if ($this->option('make-rule')) {
            $ruleNamespace[] = $this->qualifyClass(str_replace('Rules/', '', $this->option('make-rule')));
        }

        if ($this->option('include-rule')) {
            $ruleNamespace = array_merge($ruleNamespace, $this->option('include-rule'));
        }

        return empty($ruleNamespace) ? '' : 'use '.implode(";\nuse ", $ruleNamespace).';';
    }

    /**
     * @return string
     */
    protected function getStub(): string
    {
        return __DIR__.'/stubs/validation.stub';
    }
}
