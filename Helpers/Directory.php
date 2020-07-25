<?php

namespace Prymag\OrdersExporter\Helpers;

use \Magento\Framework\App\Filesystem\DirectoryList;

class Directory {

    protected $_directoryList;

    public function __construct(
        DirectoryList $directoryList
    )
    {
        # code...
        $this->_directoryList = $directoryList;
    }

    public function getExport()
    {
        # code...
        $exportDir = $this->_directoryList->getPath(DirectoryList::VAR_DIR) . '/prymag_orders_exporter';

        if(!is_dir($exportDir))
            mkdir($exportDir, 0777, true);

        return $exportDir;
    }

}