<?php

namespace Prymag\OrdersExporter\Model\CSV\ETM\OrderTraits;

trait OrderInfoTrait {

    protected $_order;

    public function getOrderDate()
    {
        # code...
        return $this->_order->getCreatedAt();
    }

    public function getOrderNumberExternal()
    {
        # code...
        return $this->_order->getIncrementId();
    }

    public function getOrderNumber()
    {
        # code...
        return $this->_order->getIncrementId();
    }
    
}