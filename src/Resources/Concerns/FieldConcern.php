<?php

/**
 * @author simon <crcms@crcms.cn>
 * @datetime 2018-04-13 21:44
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Foundation\Resources\Concerns;

use Illuminate\Support\Arr;

trait FieldConcern
{
    /**
     * @var array
     */
    protected $resourceFields = [];

    /**
     * @var string
     */
    protected $resourceType = 'except';

    /**
     * @var array
     */
    protected $scenes = [];

    /**
     * Set the keys that are supposed to be filtered out.
     *
     * @param array $fields
     * @return FieldConcern
     */
    public function hide(array $fields): self
    {
        $this->resourceFields = $fields;
        $this->resourceType = 'except';

        return $this;
    }

    /**
     * @param array $fields
     * @return FieldConcern
     */
    public function except(array $fields): self
    {
        return $this->hide($fields);
    }

    /**
     * @param string $scene
     *
     * @return FieldConcern
     */
    public function scene(string $scene): self
    {
        $this->resourceFields = $this->scenes[$scene] ?? [];
        $this->resourceType = 'only';

        return $this;
    }

    /**
     * @param array $fields
     * @return FieldConcern
     */
    public function only(array $fields): self
    {
        $this->resourceFields = $fields;
        $this->resourceType = 'only';

        return $this;
    }

    /**
     * Remove the filtered keys.
     *
     * @param $array
     * @return array
     */
    protected function filterFields(array $array): array
    {
        return Arr::{$this->resourceType}($array, $this->resourceFields);
    }
}
