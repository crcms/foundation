<?php

namespace CrCms\Foundation\Schemas;

use Illuminate\Database\Schema\Blueprint as BaseBlueprint;
use Illuminate\Database\Schema\ColumnDefinition;

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
     * @return void
     */
    public function integerUids(): void
    {
        $this->unsignedBigIntegerDefault('created_uid');
        $this->unsignedBigIntegerDefault('updated_uid');
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
     *
     * @return void
     */
    public function integerSoftDeleteUid(): void
    {
        $this->unsignedBigIntegerDefault('deleted_uid');
    }

    /**
     * @param string $column
     * @param int $default
     *
     * @return ColumnDefinition
     */
    public function unsignedBigIntegerDefault(string $column, int $default = 0): ColumnDefinition
    {
        return $this->blueprint->unsignedBigInteger($column)->default($default);
    }

    /**
     * @param string $column
     * @param int $default
     *
     * @return ColumnDefinition
     */
    public function unsignedTinyIntegerDefault(string $column, int $default = 0): ColumnDefinition
    {
        return $this->blueprint->unsignedTinyInteger($column)->default($default);
    }

    /**
     * @param string $column
     * @param int $default
     *
     * @return ColumnDefinition
     */
    public function unsignedIntegerDefault(string $column, int $default = 0): ColumnDefinition
    {
        return $this->blueprint->unsignedInteger($column)->default($default);
    }
}