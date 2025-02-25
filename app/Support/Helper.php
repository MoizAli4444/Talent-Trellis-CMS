<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class Helper
{
    /**
     * Securely find a model by column and handle 404 errors.
     *
     * @param string|Model $model  The Eloquent model class
     * @param string $column  Column name to search by
     * @param mixed $value  The value to search for
     * @param bool $withTrashed  Whether to include soft-deleted records
     * @return Model
     */
    public static function findOrAbort($model, $column, $value, $withTrashed = false)
    {
        $query = $withTrashed ? $model::withTrashed() : $model::query();
        return $query->where($column, $value)->firstOr(fn() => abort(404, 'Resource not found'));
    }
}
