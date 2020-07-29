<?php

namespace Prymag\OrdersExporter\Service;


use Prymag\OrdersExporter\Helpers\Config\SendToEmail;
use Prymag\OrdersExporter\Helpers\Config\SendToFtp;
use Prymag\OrdersExporter\Helpers\Directory;
use Prymag\OrdersExporter\Helpers\Email;
use Prymag\OrdersExporter\Helpers\Ftp;

class CsvSenderService {

    protected $_directory;
    
    protected $_email;

    protected $_ftp;

    protected $_sendToEmail;

    protected $_sendToFtp;

    public function __construct(
        Directory $directory,
        Email $email,
        Ftp $ftp,
        SendToEmail $sendToEmail,
        SendToFtp $sendToFtp
    )
    {
        # code...
        $this->_directory = $directory;
        $this->_email = $email;
        $this->_ftp = $ftp;
        $this->_sendToEmail = $sendToEmail;
        $this->_sendToFtp = $sendToFtp;
    }

    public function send($results)
    {
        # code...
        $attachments = $this->getFiles($results);

        //$this->willSendToEmail($attachments);
        $this->willSendToFTP($attachments);
    }

    public function willSendToEmail($attachments)
    {
        # code...
        $enabled = $this->_sendToEmail->getEnabled();

        if ($enabled) {
            $address = $this->_sendToEmail->getAddress();
            $this->_email->send($address, $attachments);
        }
    }

    public function willSendToFTP($attachments)
    {
        # code...
        $enabled = $this->_sendToFtp->getEnabled();
        
        if ($enabled) {
            $args = $this->_sendToFtp->getArgs();
            $this->_ftp->send($attachments, $args);
        }
    }

    /**
     * @param $results
     */
    public function getFiles($results)
    {
        # code...
        $files = [];
        foreach ($results as $result) {
            $files[] = [
                'full_path' => $this->_directory->getExport() . '/' . $result['filename'],
                'filename' => $result['filename'],
                'type' => 'text/csv'
            ];
        }

        return $files;
    }

}