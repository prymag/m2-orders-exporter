<?php

namespace Prymag\OrdersExporter\Helpers;

use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\Filesystem\Io\Ftp as FtpClient;
use Psr\Log\LoggerInterface;

class Ftp {

    protected $_file;

    protected $_ftpClient;

    protected $logger;

    public function __construct(
        File $file,
        FtpClient $ftpClient,
        LoggerInterface $logger
    )
    {
        $this->logger = $logger;
        $this->_file = $file;
        $this->_ftpClient = $ftpClient;
    }

    public function send($attachments, $args = [])
    {
        $args['passive'] = 1; // Force Passive for now
        $this->_ftpClient->open($args);

        if (count($attachments)) {
            $this->logger->info("Sending files via ftp...");
            foreach ($attachments as $attachment) {
                $content = $this->_file->fileGetContents($attachment['full_path']);

                $retries = 1;

                while ($retries < 5) {
                    try {
                        $success = $this->_ftpClient->write($attachment['filename'], $content);
                        if ($success) {
                            break;
                        } else {
                            $this->logger->notice('Unable to send file: ' . $attachment['filename']);
                            $retries++;
                        }
                    } catch (\Exception $e) {
                        $retries++;
                        $this->logger->error("Exception sending file: " . $attachment['filename'] . ' ' . $e->getMessage());
                    }
                }
            }
        }

        $this->_ftpClient->close();
    }

}