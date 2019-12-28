<?php

namespace JustIversen\SheetMapper;

class SheetMapper
{
    protected $_data;

    public function __construct()
    {
        //
    }

    public function source($data)
    {
        if (is_array($data)) {

            $this->_data = $data;

            return $this;
        } else if ($data instanceof \SplFileInfo) {

            /**  Identify the type of $inputFileName  **/
            $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($data);
            /**  Create a new Reader of the type that has been identified  **/
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
            try {

                $spreadsheet = $reader->load($data);

                $worksheet = $spreadsheet->getActiveSheet();

                $excelArray = [];

                foreach ($worksheet->getRowIterator() as $row) {

                    $cellIterator = $row->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(FALSE);

                    $nestedValueArray = [];

                    foreach ($cellIterator as $cell) {
                        array_push($nestedValueArray, $cell->getFormattedValue());
                    }
                    array_push($excelArray, $nestedValueArray);
                }

                $headings = array_shift($excelArray);

                array_walk(
                    $excelArray,
                    function (&$row, $key) use ($headings) {
                        $row = array_combine($headings, $row);
                    }
                );

                $this->_data = $excelArray;
            } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
                echo 'Error loading file: ' . $e->getMessage();
            }
        } else {
            throw new \Exception('Data is neither array or SplFileInfo');
        }

        return $this;
    }

    /**
     * Dump data as array
     *
     * @return $this
     */
    public function dumpArray()
    {
        foreach ($this->_data as $array) {
            print('////////////////////////////////////////////////////////////////');
            foreach ($array as $key => $value) {
                echo '<pre>';
                print_r($key . ' = ' . $value);
                echo '</pre>';
            }
        }

        return $this;
    }

    /**
     * A general function we can use for array_map commands. Just need
     * to pass on the $command we want to run.
     *
     * @param string $command
     * @return $this
     */
    public function map($command)
    {
        $result = [];
        foreach ($this->_data as $value) {
            if ($command == 'strtolower') {
                $value = str_replace(array('Æ', 'Ø', 'Å'), array('æ', 'ø', 'å'), $value);
            }
            if ($command == 'strtoupper') {
                $value = str_replace(array('æ', 'ø', 'å'), array('Æ', 'Ø', 'Å'), $value);
            }

            $result[] = array_map($command, $value);
        }

        $this->_data = $result;

        return $this;
    }

    /**
     * Catch method calls
     *
     * @param string $name
     * @param array|null $arguments
     * @return $this
     */
    public function __call($name, $arguments = null)
    {
        $class = "\JustIversen\SheetMapper\Mappers\\" . ucfirst($name);
        $this->_data = (new $class())->handle($this->_data, $arguments);

        return $this;
    }
}
