<?php
/**
 * @TODO: Working
 */
namespace Prymag\OrdersExporter\Helpers\Config;

class SendToFtp extends \Magento\Framework\App\Helper\AbstractHelper {

    const XML_PATH = 'Prymag_OrdersExporter/sendto_ftp/';

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
        return $this->scopeConfig->getValue(
            self::XML_PATH . 'password',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }

}