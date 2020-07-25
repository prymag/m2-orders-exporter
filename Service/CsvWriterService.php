<?php

namespace Prymag\OrdersExporter\Service;

use \Magento\Framework\File\Csv;
use \Magento\Framework\Filesystem;

use Prymag\OrdersExporter\Helpers\Directory;

class CsvWriterService {
    //
    protected $_fileSystem;
    protected $_directory;
    protected $_csvProcessor;

    public function __construct(
        Csv $csvProcessor,
        Directory $directory,
        Filesystem $filesystem
    ) {
        $this->_filesystem = $filesystem;  
        $this->_directory = $directory;
        $this->_csvProcessor = $csvProcessor;
    }

    public function setDelimiter($delimiter)
    {
        # code...
        if ($delimiter == '') {
            return $this;
        }

        if ($delimiter == 'tab') {
            $delimiter = "\t";
        }
        
        $this->_csvProcessor->setDelimiter($delimiter);
        return $this;
    }

    function write($data, $filename = 'export'){
        //
        $exportDir = $this->_directory->getExport();
        $fileName = "{$filename}.csv";
        $filePath =  $exportDir . '/' . $fileName;
    
        $this->_csvProcessor
            ->setEnclosure('"')
            ->saveData($filePath, $data);
    }

}