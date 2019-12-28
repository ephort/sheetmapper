<?php

namespace JustIversen\SheetMapper\Mappers;

class Concat
{
    public function handle($data, $arguments = null)
    {
        list($columns, $separator, $newColumnName) = $arguments;

        foreach ($data as $key => &$val) {
            $value = '';

            foreach ($columns as $column) {
                if (!array_key_exists($column, $val)) {
                    throw new \Exception("ConCat function in SheetMapper failed due to the fact, that one or more of
                    the column IDs didn't excist");
                }
                $value .= $val[$column] ?? '' . $separator;
            }

            $val[$newColumnName] = trim($value, $separator);
        }

        return $data;
    }
}
