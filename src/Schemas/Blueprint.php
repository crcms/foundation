<?php

namespace CrCms\Foundation\Schemas;

use Illuminate\Database\Schema\Blueprint as BaseBlueprint;
use Illuminate\Database\Schema\ColumnDefinition;

class Blueprint
{
    /**
     * @param BaseBlueprint $table
     *
     * @return void
     */
    public static function integerTimestamps(BaseBlueprint $table): void
    {
        $table->unsignedBigInteger('created_at')->default(0);
        $table->unsignedBigInteger('updated_at')->default(0);
    }

    /**
     * @param BaseBlueprint $table
     *
     * @return void
     */
    public static function integerUids(BaseBlueprint $table): void
    {
        static::unsignedBigIntegerDefault($table, 'created_uid');
        static::unsignedBigIntegerDefault($table, 'updated_uid');
    }

    /**
     * @param BaseBlueprint $table
     *
     * @return ColumnDefinition
     */
    public static function integerUserType(BaseBlueprint $table): ColumnDefinition
    {
        return $table->unsignedTinyInteger('user_type')->default(0);
    }

    /**
     * @param BaseBlueprint $table
     *
     * @return void
     */
    public static function integerSoftDeletes(BaseBlueprint $table): void
    {
        $table->unsignedBigInteger('deleted_at')->nullable();
    }

    /**
     * @param BaseBlueprint $table
     *
     * @return void
     */
    public static function integerSoftDeleteUid(BaseBlueprint $table): void
    {
        static::unsignedBigIntegerDefault($table, 'deleted_uid');
    }

    /**
     * @param BaseBlueprint $table
     * @param string $column
     * @param int $default
     *
     * @return ColumnDefinition
     */
    public static function unsignedBigIntegerDefault(BaseBlueprint $table, string $column, int $default = 0): ColumnDefinition
    {
        return $table->unsignedBigInteger($column)->default($default);
    }

    /**
     * @param BaseBlueprint $table
     * @param string $column
     * @param int $default
     *
     * @return ColumnDefinition
     */
    public static function unsignedTinyIntegerDefault(BaseBlueprint $table, string $column, int $default = 0): ColumnDefinition
    {
        return $table->unsignedTinyInteger($column)->default($default);
    }

    /**
     * @param BaseBlueprint $table
     * @param string $column
     * @param int $default
     *
     * @return ColumnDefinition
     */
    public static function unsignedIntegerDefault(BaseBlueprint $table, string $column, int $default = 0): ColumnDefinition
    {
        return $table->unsignedInteger($column)->default($default);
    }
}