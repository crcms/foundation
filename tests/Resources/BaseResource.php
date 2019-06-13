<?php

namespace CrCms\Foundation\Tests\Resources;

use CrCms\Foundation\Resources\JsonResource;

class BaseResource extends JsonResource
{
    protected $scenes = [
        'default' => ['f1', 'f2'],
        'scene1' => ['f2', 'f3', 'f4'],
    ];

    protected $casts = [
        'f3' => 'str',
    ];

    public function getF4Field($request)
    {
        return 'f4';
    }
}
