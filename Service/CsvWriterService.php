<?php

namespace Prymag\OrdersExporter\Service;

use \Magento\Framework\File\Csv;
use \Magento\Framework\App\Filesystem\DirectoryList;
use \Magento\Framework\Filesystem;

class CsvWriterService {
    //
    protected $_fileSystem;
    protected $_directoryList;
    protected $_csvProcessor;

    public function __construct(
        Csv $csvProcessor,
        DirectoryList $directoryList,
        Filesystem $filesystem
    ) {
        $this->_filesystem = $filesystem;  
        $this->_directoryList = $directoryList;
        $this->_csvProcessor = $csvProcessor;
    }

    public function getExportDir()
    {
        # code...
        $exportDir = $this->_directoryList->getPath(DirectoryList::VAR_DIR) . '/prymag_orders_exporter';

        if(!is_dir($exportDir))
            mkdir($exportDir, 0777, true);

        return $exportDir;
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
        $exportDir = $this->getExportDir();
        $fileName = "{$filename}.csv";
        $filePath =  $exportDir . '/' . $fileName;
    
        $this->_csvProcessor
            ->setEnclosure('"')
            ->saveData($filePath, $data);
    }

}