<?php
namespace App\Helpers;

class QueryBuilder
{
    public function prepareForInsert(array $columnsWithValues)
    {
        $cols =  '`' . implode('`,`', array_keys($columnsWithValues)) . '`';
        $vals =  "'" . implode("','", array_values($columnsWithValues)) . "'";
        return ['columns' => $cols , 'values' => $vals];
    }

    public function prepareForSelect(array $items)
    {
        return implode(',', $items);
    }

}