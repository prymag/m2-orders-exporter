<?php

namespace Prymag\OrdersExporter\Model\CSV\ETM\OrderTraits;

trait CustomerTrait {

    protected $_order;

    public function getFirstName()
    {
        # code...
        return $this->_order->getCustomerFirstName();
    }

    public function getLastName()
    {
        # code...
        return $this->_order->getCustomerLastName();
    }

    public function getDateOfBirth()
    {
        # code...
        return $this->_order->getCustomerDateOfBirth();
    }

    public function getGender()
    {
        # code...
        return $this->_order->getCustomerGender();
    }

    public function getEmail()
    {
        # code...
        return $this->_order->getCustomerEmail();
    }

    public function getIPNo()
    {
        # code...
        return $this->_order->getRemoteIp();
    }

}