<?php

namespace Prymag\OrdersExporter\Service;

use Prymag\OrdersExporter\Helpers\Config\SendToEmail;
use Prymag\OrdersExporter\Helpers\Config\SendToFtp;
use Prymag\OrdersExporter\Helpers\Email;

class CsvSenderService {

    protected $_email;

    protected $_sendToEmail;

    protected $_sendToFtp;

    public function __construct(
        Email $email,
        SendToEmail $sendToEmail,
        SendToFtp $sendToFtp
    )
    {
        # code...
        $this->_email = $email;
        $this->_sendToEmail = $sendToEmail;
        $this->_sendToFtp = $sendToFtp;
    }

    public function send($results)
    {
        # code...
        $this->willSendToEmail();
    }

    public function willSendToEmail()
    {
        # code...
        $enabled = $this->_sendToEmail->getEnabled();

        if ($enabled) {
            print "enabled";

            $address = $this->_sendToEmail->getAddress();
        }

        $this->_email->send($address);
        print $address;
    }

}