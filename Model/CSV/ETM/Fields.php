<?php

namespace Prymag\OrdersExporter\Model\CSV\ETM;

class Fields {

    protected $_fields = [
        //'Order Increment ID', // For debugging only
        'Type',
        'First name',
        'Last name',
        'Address',
        'Postal Code',
        'City',
        'Country',
        'SSN',
        'Date of birth',
        'Gender',
        'Phone', 
        'Email',
        'Mail advertisement',
        'TM',
        'Email information',
        'Email advertisement',
        'SMS information',
        'SMS advertisement',
        'Approved address sale',
        'Campaign code',
        'Seller company',
        'IP no',
        'Order date',
        'Order no external',
        'Freight',
        'Payment Fee',
        'Way of payment',
        'Way of delivery',
        'Terms of delivery',
        'Terms of payment',
        'Article no',
        'Size',
        'Qty',
    ];


    public function get()
    {
        # code...
        return $this->_fields;
    }

}