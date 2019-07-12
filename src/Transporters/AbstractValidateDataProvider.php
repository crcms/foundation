<?php

/**
 * @author simon <simon@crcms.cn>
 * @datetime 2018-11-14 22:35
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Foundation\Transporters;

use Illuminate\Contracts\Validation\ValidatesWhenResolved;
use CrCms\Foundation\Transporters\Concerns\ValidateConcern;
use Illuminate\Support\Arr;
use Illuminate\Contracts\Validation\Factory;

/**
 * Class AbstractValidateDataProvider.
 */
abstract class AbstractValidateDataProvider extends AbstractDataProvider implements ValidatesWhenResolved
{
    use ValidateConcern {
        //validateResolved as validateConcernValidateResolved;
    }

    /**
     * @var array
     */
    protected $scenes = [];

    /**
     * @var bool
     */
    public $isAutoValidate = true;

    /**
     * @param $scene
     *
     * @return AbstractValidateDataProvider
     */
    public function scenes($scene): self
    {
        $this->scenes = (array)$scene;
        return $this;
    }

    /**
     * @param Factory $factory
     *
     * @return mixed
     */
    public function validator(Factory $factory)
    {
        // 自动验证，则开启之前的默认验证走，rules方法
        if ($this->isAutoValidate) {
            return $this->createDefaultValidator($factory);
        }

        $rules = $this->app()->call([$this, 'sceneRules']);

        if (! empty($this->scenes)) {
            $rules = array_reduce(Arr::only($rules, $this->scenes), 'array_merge', []);
        } else {
            $rules = [];
        }

        return $factory->make(
            $this->validationData(), $rules,
            $this->messages(), $this->attributes()
        );
    }

    /**
     *
     * @return array
     */
    public function sceneRules(): array
    {
        return [];
    }
}
