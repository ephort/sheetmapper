<?php

namespace JustIversen\SheetMapper\Mappers;

class ToUpper
{
    public function handle($data)
    {
        $result = [];
        foreach ($data as $value) {
            $result[] = array_map('mb_strtoupper', $value);
        }
        return $result;
    }
}
