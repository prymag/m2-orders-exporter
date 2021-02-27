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

        return explode(',', $storeIds);
    }

    protected function getFilenames($filenames = false) {
        //
        if (!$filenames)
            return [];

        return explode(',', $filenames);
    }

    public function process($params)
    {
        # code...
        $storeIds = $this->getStoreIds($params['storeIds']);
        $filters = $this->getOrderDateFilters($params['rangeStart']);        
        $filenames = $this->getFilenames($params['filenames']);
        
        $result = [];
        foreach($storeIds as $index => $storeId) {
            // 
            $filters['store_id'] = ['eq' => $storeId];
            $csvData = $this->getCSVData($filters);

            if (!$csvData) {
                continue;
            }
            
            $filename = $this->makeFilename($filters, $filenames, $index);

            $this->_csvWriterService
                ->setDelimiter($params['delimiter'])
                ->write($csvData, $filename);

            $result[] = [
                'storeId' => $storeId,
                'total' => count($csvData) - 1,
                'filename' => $filename . '.csv'
            ];
        }

        return $result;
    }

    protected function getCSVData($filters)
    {
        $orderCollection = $this->_orderList->getCollection($filters)->load();

        if (!$orderCollection->getSize()) {
            return;
        }
        
        $providerParams = [
            'collection' => $orderCollection,
            'fields' => $this->_fields,
            'orderType' => 'I'
        ];
        $dataArray = $this->_csvDataProvider->create($providerParams)->toArray();
        
        return $dataArray;
    }

    protected function getOrderDateFilters($rangeStart)
    {
        # code...
        // Set default rangeStart if empty
        $rangeStart = !$rangeStart ? '-24 hours' : "-" . $rangeStart;

        $toDateTime = new \DateTime();
        
        $fromDateTime = clone $toDateTime;
        $fromDateTime->modify($rangeStart);

        $from = $this->_dateTime->gmtDate(null, $fromDateTime->getTimestamp());
        $to = $this->_dateTime->gmtDate(null, $toDateTime->getTimestamp());
        
        return ['created_at' => ['from' => $from, 'to' => $to]];
    }

    protected function makeFilename($filters, $filenames, $index)
    {
        # code...
        
        if (!$filenames || !isset($filenames[$index])) {
            return "Export-{$filters['store_id']['eq']}-{$filters['created_at']['to']}";
        }

        return "{$filenames[$index]}-{$filters['created_at']['to']}";
    }
}