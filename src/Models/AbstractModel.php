<?php

namespace CrCms\Foundation\Models;

use Illuminate\Database\Eloquent\Model as BaseModel;

abstract class AbstractModel extends BaseModel
{
    /**
     * @var string
     */
    protected $dateFormat = 'U';

    /**
     * @var array
     */
    protected $guarded = [];
}
