<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;

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
        $fileSystem = new Filesystem();
        if(!$fileSystem->exists($this->csvDirectory)) {
        $fileSystem->mkdir($this->csvDirectory);
        }
        $fileName = $this->csvDirectory.'/export_'.date('d-m-Y-H:i:s').'.csv';
        $file = fopen($fileName, "w");

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