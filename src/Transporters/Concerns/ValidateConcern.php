<?php

namespace CrCms\Foundation\Transporters\Concerns;

use Illuminate\Contracts\Validation\Factory;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\ValidatesWhenResolvedTrait;

/**
 * Trait Validate.
 */
trait ValidateConcern
{
    use ValidatesWhenResolvedTrait;

    /**
     * @var Application
     */
    protected $app;

    /**
     * @return Container
     */
    public function app(): Container
    {
        if (is_null($this->app)) {
            $this->app = app();
        }

        return $this->app;
    }

    /**
     * Get the validator instance for the request.
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function getValidatorInstance()
    {
        $factory = $this->app()->make(Factory::class);

        if (method_exists($this, 'validator')) {
            $validator = $this->app()->call([$this, 'validator'], compact('factory'));
        } else {
            $validator = $this->createDefaultValidator($factory);
        }

        if (method_exists($this, 'withValidator')) {
            $this->withValidator($validator);
        }

        return $validator;
    }

    /**
     * Create the default validator instance.
     *
     * @param  \Illuminate\Contracts\Validation\Factory $factory
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function createDefaultValidator(Factory $factory)
    {
        return $factory->make(
            $this->validationData(), $this->app()->call([$this, 'rules']),
            $this->messages(), $this->attributes()
        );
    }

    public function rules()
    {
        return [];
    }

    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    protected function validationData()
    {
        return $this->all();
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        throw (new ValidationException($validator))
            ->errorBag('default');
    }
}
