<?php

namespace JustIversen\SheetMapper\Mappers;

class CheckForNull
{
    public function handle($data)
    {
        $result = [];
        foreach ($data as $value) {
            $result[] = array_map(static function ($value) {
                return $value === '' ? $value = 'null' : $value;
            }, $value);
        }
        return $result;
    }
}
