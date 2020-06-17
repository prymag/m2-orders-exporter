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

    protected function getStoreIds($storeIds = false)
    {
        if (!$storeIds)
            return [];

        $ids = explode(',', $storeIds);

        return $ids;
    }

    public function process($params)
    {
        # code...
        
        $storeIds = $this->getStoreIds($params['storeIds']);
        
        $result = [];

        foreach($storeIds as $index => $storeId) {
            //
            $filters = $this->getOrderFilters($storeId, $params['range']);
            
            $orderCollection = $this->_orderList->getCollection($filters)->load();
            
            $providerParams = [
                'collection' => $orderCollection,
                'fields' => $this->_fields,
                'orderType' => 'I'
            ];
            $dataArray = $this->_csvDataProvider->create($providerParams)->toArray();
            
            $filename = $this->makeFilename($filters, $params['filenames'], $index);
            $this->_csvWriterService->write($dataArray, $filename);

            $result[] = [
                'storeId' => $storeId,
                'total' => count($dataArray) - 1
            ];
        }

        return $result;
    }

    protected function getOrderFilters($storeId, $range)
    {
        # code...
        // Set default range if empty
        $range = !$range ? '-24 hours' : $range;

        $toDateTime = new \DateTime();

        $fromDateTime = clone $toDateTime;
        $fromDateTime->modify($range);

        $from = $this->_dateTime->gmtDate(null, $fromDateTime->getTimestamp());
        $to = $this->_dateTime->gmtDate(null, $toDateTime->getTimestamp());
        
        $filters = [
            'created_at' => ['from' => $from, 'to' => $to],
            'store_id' => ['eq' => $storeId]
        ];

        return $filters;
    }

    public function makeFilename($filters, $filenames, $index)
    {
        # code...
        
        if (!$filenames) {
            return "Export-{$filters['store_id']['eq']}-{$filters['created_at']['to']}";
        }

        $filename = explode(',', $filenames)[$index];
        return "{$filename}-{$filters['created_at']['to']}";
    }
}