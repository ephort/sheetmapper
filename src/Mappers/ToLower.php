<?php

namespace JustIversen\SheetMapper\Mappers;

class ToLower
{
    public function handle($data)
    {
        $result = [];
        foreach ($data as $value) {
            $result[] = array_map('mb_strtolower', $value);
        }
        return $result;
    }
}
