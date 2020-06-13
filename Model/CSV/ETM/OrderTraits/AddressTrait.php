<?php

namespace Prymag\OrdersExporter\Model\CSV\ETM\OrderTraits;

trait AddressTrait {

    protected $_addressData;
    protected $_order;

    public function getPhone()
    {
        # code...
        $this->willSetAddressData();
        return $this->_addressData->getTelephone();
    }

    public function getAddress() {
        //
        $this->willSetAddressData();
        return $this->_addressData->getStreet()[0];
    }

    protected function willSetAddressData() {
        //
        if ($this->_addressData) {
            return;
        }

        $this->_addressData = $this->_order->getShippingAddress();
    }

    public function getPostalCode()
    {
        # code...
        $this->willSetAddressData();
        return $this->_addressData->getPostCode();
    }

    public function getCity()
    {
        # code...
        $this->willSetAddressData();
        return $this->_addressData->getCity();
    }

    public function getCountry()
    {
        # code...
        $this->willSetAddressData();
        return $this->_addressData->getCountryId();
    }

}