<?php

namespace Prymag\OrdersExporter\Helpers;

use \Magento\Framework\Stdlib\DateTime\TimezoneInterface;

class DateHelper {

    protected $_timezone;

    public function __construct(
        TimezoneInterface $timezone
    ) {
        # code...
        $this->_timezone = $timezone;
    }

    /**
     * Parse the date to match the system date
     */
    public function format($date, $format = 'M d, Y H:i:s A')
    {
        # code...
        $theDate = new \DateTime($date);
        return $this->_timezone->date($theDate)->format($format);
    }

}