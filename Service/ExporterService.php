<?php

namespace Prymag\OrdersExporter\Service;

use Magento\Framework\Stdlib\DateTime\DateTime;
use Prymag\OrdersExporter\Model\OrderCustomerGroup;
use Prymag\OrdersExporter\Model\OrderList;
use Prymag\OrdersExporter\Model\CSV\ETM\DataProviderFactory;
use Prymag\OrdersExporter\Model\CSV\ETM\Fields;

class ExporterService {
    //
    protected $_csvDataProvider;
    protected $_csvWriterService;
    protected $_dateTime;
    protected $_fields;
    protected $_orderList;

    public function __construct(
        DataProviderFactory $csvDataProvider,
        DateTime $dateTime,
        CsvWriterService $csvWriterService,
        Fields $fields,
        OrderList $orderList
    ) {
        # code...
        $this->_csvDataProvider = $csvDataProvider;
        $this->_csvWriterService = $csvWriterService;
        $this->_dateTime = $dateTime;
        $this->_fields = $fields;
        $this->_orderList = $orderList;
    }

    public function process()
    {
        # code...
        $orderCollection = $this->_orderList
            ->getCollection($this->getOrderFilters())
            ->load();

        $providerParams = [
            'collection' => $orderCollection,
            'fields' => $this->_fields,
            'orderType' => 'I'
        ];

        $dataArray = $this->_csvDataProvider
            ->create($providerParams)
            ->toArray();

        $this->_csvWriterService->write($dataArray);
        return ['total' => count($dataArray)-1];
    }

    public function getOrderFilters()
    {
        # code...
        // We only want orders created 24 hours from the current time
        $toTimeStamp = $this->_dateTime->timestamp();
        //$toTimeStamp = $this->_dateTime->gmtTimestamp('Mar 21, 2020 12:15:34 PM'); // Testing
        //$fromTimeStamp = strtotime('-24 hours', $toTimeStamp);
        $fromTimeStamp = strtotime('-6 months', $toTimeStamp);

        $from = $this->_dateTime->gmtDate(null, $fromTimeStamp);
        $to = $this->_dateTime->gmtDate(null, $toTimeStamp);

        $filters = [
            'created_at' => ['from' => $from, 'to' => $to]
        ];

        return $filters;
    }

}