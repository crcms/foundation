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

/**
 * Class AbstractValidateDataProvider.
 */
abstract class AbstractValidateDataProvider extends AbstractDataProvider implements ValidatesWhenResolved
{
    use ValidateConcern {
        validateResolved as validateConcernValidateResolved;
    }

    /**
     * @var array
     */
    protected $scenes = [];

    /**
     * @var bool
     */
    protected $isAutoValidate = true;

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
     *
     * @return void
     */
    public function validateResolved()
    {
        if ($this->isAutoValidate) {
            $this->validateConcernValidateResolved();
        }
    }

    /**
     * @param Factory $factory
     *
     * @return mixed
     */
    protected function validator(Factory $factory)
    {
        $rules = $this->app()->call([$this, 'rules']);

        if (! empty($this->scenes)) {
            $rules = array_reduce(\Illuminate\Support\Arr::only($rules, $this->scenes), 'array_merge', []);
        } else {
            // 如果为空则表示全部是scene,
            // 需要取下一层
            $rules = Arr::only($rules, array_keys($this->all()));
            if (empty($rules)) {
                $rules = array_reduce($rules, 'array_merge', []);
            }
        }

        return $factory->make(
            $this->validationData(), $rules,
            $this->messages(), $this->attributes()
        );
    }
}
