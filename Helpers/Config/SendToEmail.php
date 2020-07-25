<?php

namespace Prymag\OrdersExporter\Helpers\Config;

class SendToEmail extends \Magento\Framework\App\Helper\AbstractHelper {

    const XML_PATH = 'Prymag_OrdersExporter/sendto_email/';

    public function getEnabled($store = null)
    {
        # code...
        return $this->scopeConfig->getValue(
            self::XML_PATH . 'enabled',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    public function getAddress($store = null)
    {
        # code...
        return $this->scopeConfig->getValue(
            self::XML_PATH . 'address',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }

}