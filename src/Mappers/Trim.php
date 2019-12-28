<?php

namespace JustIversen\SheetMapper\Mappers;

class Trim
{
    public function handle($data)
    {
        $result = [];

        foreach ($data as $value) {
            $result[] = array_map('trim', $value);
        }
        return $result;
    }
}
