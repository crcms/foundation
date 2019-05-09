<?php

namespace CrCms\Foundation\Schemas;

use Illuminate\Database\Schema\Blueprint as BaseBlueprint;

class Blueprint
{
    /**
     * @var BaseBlueprint
     */
    protected $blueprint;

    /**
     * @param BaseBlueprint $blueprint
     */
    public function __construct(BaseBlueprint $blueprint)
    {
        $this->blueprint = $blueprint;
    }

    /**
     *
     * @return void
     */
    public function integerTimestamps(): void
    {
        $this->blueprint->unsignedBigInteger('created_at')->default(0);
        $this->blueprint->unsignedBigInteger('updated_at')->default(0);
    }

    /**
     *
     * @return void
     */
    public function integerSoftDeletes(): void
    {
        $this->blueprint->unsignedBigInteger('deleted_at')->nullable();
    }

    /**
     * @param string $column
     * @param int $default
     *
     * @return void
     */
    public function unsignedBigIntegerDefault(string $column, int $default = 0): void
    {
        $this->blueprint->unsignedBigInteger($column)->default($default);
    }
}