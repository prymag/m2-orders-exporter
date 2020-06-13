<?php

namespace Prymag\OrdersExporter\Model\CSV\ETM\OrderTraits;

trait DeliveryTrait {

    protected $_order;

    public function getWayOfDelivery()
    {
        # code...
        $code = $this->_order
            ->getStore()
            ->getWebsite()
            ->getCode();

        switch($code) {
            case 'almea':
                return 'Letter, standard';
            case 'caredirect_se';
            case 'halsorutan_se';
                return 'Brev, 39';
            default:
                return '';
        }
    }

    public function getTermsOfDelivery()
    {
        # code...
        return 'Fritt vårt lager';
    }

    public function getDeliveryDate()
    {
        # code...
        return '';
    }


}