<?php

use Illuminate\Support\Facades\DB;

if (!function_exists('getTableColumns')) {
    function getTableColumns($table, $skip = [], $showcoltype = false)
    {
        $columns = DB::getSchemaBuilder()->getColumnListing($table);
        if (!empty($skip)) {
            $columns = array_diff($columns, $skip);
        }

        $cols = [];
        foreach ($columns as $key => $column) {
            $cols[$column] = ucwords(str_replace('_', ' ', $column));
        }

        return $cols;
    }
}
