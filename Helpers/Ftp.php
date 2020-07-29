<?php

namespace Prymag\OrdersExporter\Helpers;

use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\Filesystem\Io\Ftp as FtpClient;


class Ftp {

    protected $_file;

    protected $_ftpClient;

    public function __construct(
        File $file,
        FtpClient $ftpClient
    )
    {
        # code...
        $this->_file = $file;
        $this->_ftpClient = $ftpClient;
    }

    public function send($attachments, $args = [])
    {
        # code..
        $this->_ftpClient->open($args);

        if (count($attachments)) {
            foreach ($attachments as $attachment) {
                $content = $this->_file->fileGetContents($attachment['full_path']);
                $this->_ftpClient->write($attachment['filename'], $content);
            }
        }

        $this->_ftpClient->close();
    }

}