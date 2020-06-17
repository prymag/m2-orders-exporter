<?php

namespace Prymag\OrdersExporter\Model\CSV\ETM\OrderTraits;

trait StoreInfoTrait {

    protected $_scopeConfig;

    public function getLanguage() 
    {
        //
        $value = 'general/locale/code';
        $store = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        $storeId = $this->_order->getStoreId();
        $storeCode = $this->_scopeConfig
            ->getValue($value, $store, $storeId);

        $exploded = explode('_', $storeCode);

        return $exploded[0];
    }

    public function getCountry() 
    {
        //
        $value = 'general/country/default';
        $store = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        $storeId = $this->_order->getStoreId();
        $country = $this->_scopeConfig
            ->getValue($value, $store, $storeId);

        return $country;
    }

}