<?php

/**
 * This file supports export and imports actions
 * @category Service
 * @Package Virtua_Internship
 * @copyright Copyright (c) 2018 Virtua (http://www.wearevirtua.com)
 * @author Maciej Skalny contact@wearevirtua.com
 */

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;

/**
 * Class CsvActions
 * @package App\Service
 */
class CsvActions{

    /**
     * Directory for .csv files.
     * @var string
     */
    private $csvDirectory;

    /**
     * CsvActions constructor.
     * @param $csvDirectory
     */
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