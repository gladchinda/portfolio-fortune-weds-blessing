<?php

namespace App\Http\Controllers\Core\Support;

class ArrayInspector
{
    public static function isIndexed(array $data)
    {
        return empty(array_filter($data, function($key) {
            return !preg_match("/^\d+$/", $key);
        }, ARRAY_FILTER_USE_KEY));
    }

    public static function isAssoc(array $data)
    {
        return empty(array_filter($data, function($key) {
            return preg_match("/^\d+$/", $key);
        }, ARRAY_FILTER_USE_KEY));
    }
}
