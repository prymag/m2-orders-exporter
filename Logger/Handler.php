<?php
namespace Prymag\OrdersExporter\Logger;

use Monolog\Logger;

class Handler extends \Magento\Framework\Logger\Handler\Base
{
    protected $loggerType = Logger::INFO;

    protected $fileName = '/var/log/prymag_orders_exporter.log';
}