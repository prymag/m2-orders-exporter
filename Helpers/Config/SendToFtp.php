<?php
/**
 * @TODO: Working
 */
namespace Prymag\OrdersExporter\Helpers\Config;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\Encryption\EncryptorInterface;

class SendToFtp extends \Magento\Framework\App\Helper\AbstractHelper {

    const XML_PATH = 'Prymag_OrdersExporter/sendto_ftp/';

    protected $_encryptor;

    public function __construct(
        Context $context,
        EncryptorInterface $encryptor
    )
    {
        $this->_encryptor = $encryptor;
        parent::__construct($context);
    }

    public function getEnabled($store = null)
    {
        # code...
        return $this->scopeConfig->getValue(
            self::XML_PATH . 'enabled',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    public function getHost($store = null)
    {
        # code...
        return $this->scopeConfig->getValue(
            self::XML_PATH . 'host',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    public function getPort($store = null)
    {
        # code...
        return $this->scopeConfig->getValue(
            self::XML_PATH . 'port',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    public function getUsername($store = null)
    {
        # code...
        return $this->scopeConfig->getValue(
            self::XML_PATH . 'username',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    public function getPassword($store = null)
    {
        # code...
        $password = $this->scopeConfig->getValue(
            self::XML_PATH . 'password',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );

        return $this->_encryptor->decrypt($password);
    }

    public function getArgs()
    {
        # code...
        return [
            'host' => $this->getHost(),
            'port' => $this->getPort(),
            'user' => $this->getUsername(),
            'password' => $this->getPassword(),
        ];
    }

}