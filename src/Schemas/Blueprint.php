<?php

namespace CrCms\Foundation\Schemas;

use Illuminate\Database\Schema\ColumnDefinition;

class Blueprint
{
    /**
     *
     * @return void
     */
    public function integerTimestamps(): void
    {
        $this->unsignedBigInteger('created_at')->default(0);
        $this->unsignedBigInteger('updated_at')->default(0);
    }

    /**
     *
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
        $this->unsignedBigInteger('deleted_at')->nullable();
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
        return $this->unsignedBigInteger($column)->default($default);
    }

    /**
     * @param string $column
     * @param int $default
     *
     * @return ColumnDefinition
     */
    public function unsignedTinyIntegerDefault(string $column, int $default = 0): ColumnDefinition
    {
        return $this->unsignedTinyInteger($column)->default($default);
    }

    /**
     * @param string $column
     * @param int $default
     *
     * @return ColumnDefinition
     */
    public function unsignedIntegerDefault(string $column, int $default = 0): ColumnDefinition
    {
        return $this->unsignedInteger($column)->default($default);
    }
}