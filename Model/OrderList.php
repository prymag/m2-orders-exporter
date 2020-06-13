<?php

namespace Prymag\OrdersExporter\Model;

use \Magento\Sales\Model\ResourceModel\Order\CollectionFactory;

class OrderList {
    //
    protected $_orderCollectionFactory;

    public function __construct(
        CollectionFactory $orderCollectionFactory
    ) {
        # code...
        $this->_orderCollectionFactory = $orderCollectionFactory;
    }

    public function getCollection($filters = [])
    {
        # code...
        $collection = $this->_orderCollectionFactory->create()
            ->addAttributeToSelect('*');

        if (count($filters)) {
            foreach($filters as $key => $value) {
                $collection->addFieldToFilter($key, $value);
            }
        }
     
        return $collection;
    }

}