<?php

namespace App\Service;

class CsvActions{

    private $csvDirectory;

    public function __construct($csvDirectory)
    {
        $this->csvDirectory = $csvDirectory;
    }

    /**
     * @param array|null $data
     */
    public function createCsvFile(?Array $data)
    {
        $file = fopen("./public/uploads/csv/export_".date('d-m-Y-H:i:s').'.csv', "w");

        foreach($data as $line){
            fputcsv(
                $file,
                $line,
                ','
            );
        }

        fclose($file);
    }

}