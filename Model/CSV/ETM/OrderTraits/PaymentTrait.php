<?php

namespace Prymag\OrdersExporter\Model\CSV\ETM\OrderTraits;

trait PaymentTrait {

    protected $_order;

    protected $_payment;

    protected $_paymentMethod;

    public function getPaymentFee()
    {
        # code...
        // payment fee for the moment is 0 the amount to pay will be calculated by the system.
        // see email dated Jun 12, 2020, 11:23 PM
        return 0;
    }

    protected function getPayment() {
        //
        if (!$this->_payment) {
            $this->_payment = $this->_order->getPayment();
        }

        return $this->_payment;
    }

    protected function getPaymentMethod() {
        //
        if (!$this->_paymentMethod) {
            $this->_paymentMethod = $this->getPayment()->getMethod();
        }

        return $this->_paymentMethod;
    }

    public function getWayOfPayment()
    {
        # code..
        return $this->getPayment()
            ->getMethodInstance()
            ->getTitle();
    }

    public function getPaymentType()
    {
        # code...

        return $this->getPayment()
            ->getAdditionalInformation();
    }

    public function getPaymentServiceProvider()
    {
        # code...
        $method = $this->getPaymentMethod();

        switch ($method) {
            case 'dibseasycheckout':
                return 'dibs';
            case 'klarna_kco':
                return 'klarna';
            case 'paypal':
                return 'paypal';
            default:
                return $method;
        }
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