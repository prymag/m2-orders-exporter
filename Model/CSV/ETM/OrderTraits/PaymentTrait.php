<?php

namespace Prymag\OrdersExporter\Model\CSV\ETM\OrderTraits;

trait PaymentTrait {

    protected $_order;

    public function getPaymentFee()
    {
        # code...
        // payment fee for the moment is 0 the amount to pay will be calculated by the system.
        // see email dated Jun 12, 2020, 11:23 PM
        return 0;
    }

    public function getWayOfPayment()
    {
        # code...
        return $this->_order
            ->getPayment()
            ->getMethodInstance()
            ->getTitle();
    }

    public function getTermsOfPayment()
    {
        # code...
        return '14 dager netto';
    }

    public function getFreight()
    {
        # code...
        return $this->_order->getShippingAmount();
    }

}