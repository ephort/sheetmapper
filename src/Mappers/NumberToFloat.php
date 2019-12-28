<?php

namespace JustIversen\SheetMapper\Mappers;

/**
 * Changes number formatting from comma (1.000,00) to dot (1000.00)
 */
class NumberToFloat
{
    public function handle($data, $arguments = null)
    {
        list($columns) = $arguments;
        foreach ($data as &$row) {
            foreach ($columns as $column) {
                $row[$column] = $this->convert($row[$column]);
            }
        }

        return $data;
    }

    /**
     * Do the conversion
     * 
     * @param mixed  $number
     * @return float
     */
    protected function convert($number)
    {
        $number = str_replace(",", ".", $number);
        $number = preg_replace('/\.(?=.*\.)/', '', $number);

        return floatval($number);
    }
}
