<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

if (!function_exists('findOrAborts')) { // ✅ Function name is `findOrAborts`
    function findOrAborts($modelClass, $column, $value, $withTrashed = false)
    {
        $query = $withTrashed ? $modelClass::withTrashed() : $modelClass::query();
        // return $query->where($column, $value)->firstOr(fn() => abort(Response::HTTP_NOT_FOUND, 'Resource not found'));
        return $query->where($column, $value)->firstOr(function () use ($modelClass) {
            session()->flash('error', 'Resource not found');
            return redirect()->route(Str::pluralStudly(strtolower(class_basename($modelClass))) . '.index')->send();
        });
    }
}

// if (!function_exists('authorizeOwnership')) {
//     function authorizeOwnership($model)
//     {
//         if (!Auth::check() || $model->user_id !== Auth::id()) {
//             Redirect::route(Str::plural(Str::lower(class_basename($model))) . '.index')
//                 ->with('error', 'Not allowed to perform this action')
//                 ->send();
//             exit(); // Stop further execution
//         }

//         return $model;
//     }
// }


if (!function_exists('authorizeOwnership')) {
    function authorizeOwnership($model)
    {
        if (!Auth::check() || $model->user_id !== Auth::id()) {
            session()->flash('error', 'Not allowed to perform this action'); // Store session message
            abort(redirect()->route(Str::plural(Str::lower(class_basename($model))) . '.index'));
        }

        return $model;
    }
}
