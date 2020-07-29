<?php

namespace Prymag\OrdersExporter\Helpers;

use Magento\Framework\App\Area;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Model\Store;
use Prymag\OrdersExporter\Mail\Template\TransportBuilder;

class Email extends AbstractHelper
{
    protected $_directory;
    protected $_file;
    protected $_inlineTranslation;
    protected $_storeManager;
    protected $_transportBuilder;

    public function __construct(
        Context $context,
        Directory $directory,
        File $file,
        TransportBuilder $transportBuilder,
        StoreManagerInterface $storeManager,
        StateInterface $state
    )
    {
        $this->_directory = $directory;
        $this->_file = $file;
        $this->_transportBuilder = $transportBuilder;
        $this->_storeManager = $storeManager;
        $this->_inlineTranslation = $state;
        parent::__construct($context);
    }

    public function send($toEmail, $attachments = [])
    {
        $transportBuilder = $this->getTransportBuilder();
        $transportBuilder->addTo($toEmail);

        if (count($attachments)) {
            foreach ($attachments as $attachment) {
                $transportBuilder->addAttachment(
                    $this->_file->fileGetContents($attachment['full_path']),
                    $attachment['filename'],
                    $attachment['type']
                );
            }
        }

        $transport = $transportBuilder->getTransport();

        $transport->sendMessage();

        $this->_inlineTranslation->resume();
    }

    protected function getTransportBuilder()
    {
        # code...
        $templateId = 'prymag_orders_exporter_default'; // template id
        // template variables pass here
        $templateVars = [
            'msg' => 'test',
            'msg1' => 'test1'
        ];
        $from = $this->getFrom();

        $this->_inlineTranslation->suspend();

        $templateOptions = [
            'area' => Area::AREA_FRONTEND,
            'store' => Store::DEFAULT_STORE_ID
        ];

        return $this->_transportBuilder->setTemplateIdentifier($templateId)
            ->setTemplateOptions($templateOptions)
            ->setTemplateVars($templateVars)
            ->setFrom($from);
    }

    protected function getFrom()
    {
        # code...
        $from = [
            // Default General Email Address
            'email' => $this->scopeConfig->getValue('trans_email/ident_general/email'),
            // Default General Email Name
            'name' => $this->scopeConfig->getValue('trans_email/ident_general/name') 
        ];

        return $from;
    }
}