<?php

namespace Prymag\OrdersExporter\Model\CSV\ETM;

use \Magento\Sales\Model\ResourceModel\Order\Collection as OrderCollection;
use Prymag\OrdersExporter\Helpers\DateHelper;
use Prymag\OrdersExporter\Model\CSV\ETM\ETMOrderFactory;

class DataProvider {

    protected $_collection;
    protected $_dateHelper;
    protected $_etmOrderFactory;
    protected $_fields;
    protected $_orderType;
    protected $_productRepository;

    /**
     * Constructor
     * 
     * @param Array $fields
     * @param String $orderType
     * @param DateHelper $dateHelper
     * @param ETMOrderFactory $_etmOrderFactory
     * @param OrderCollection $collection
     * 
     * @return DataProvider
     */
    public function __construct(
        $fields,
        $orderType,
        DateHelper $dateHelper,
        ETMORderFactory $etmOrderFactory,
        OrderCollection $collection,
        \Magento\Catalog\Model\ProductRepository $productRepository
    ) {
        # code...
        $this->_collection = $collection;
        $this->_dateHelper = $dateHelper;
        $this->_etmOrderFactory = $etmOrderFactory;
        $this->_fields = $fields;
        $this->_orderType = $orderType;
        
        $this->_productRepository = $productRepository;

        return $this;
    }

    public function setCollection(OrderCollection $collection)
    {
        # code...
        $this->_collection = $collection;
        return $this;
    }

    public function toArray() {
        //
        $headers = $this->_fields->get();
        $data = [$headers];
        
        foreach($this->_collection as $order) {
            $this->processRow($order, $data);
        }

        return $data;
    }

    /**
     * Process Row 
     * 
     * @param Magento\Sales\Model\Order $order
     * @param Array &$data 
     */
    private function processRow($order, &$data = [])
    {
        # code...
        $info = array_map(
            [$this, 'encodeSpecialCharacters'], 
            $this->getPrimaryInfo($order)
        );
        
        // We use ArrayObject to easily create
        // a duplicate of the `$info` array
        $info = new \ArrayObject($info);
        
        $orderItems = $this->getOrderItems($order);

        foreach($orderItems as $orderItem) {
            //
            $data[] = array_merge($info->getArrayCopy(), $orderItem);
        }
        //
    }

    /**
     * Get Primary Information
     * 
     * @param Magento\Sales\Model\Order $order
     * 
     * @return Array
     */
    public function getPrimaryInfo($order)
    {
        # code...
        $params = [
            'order' => $order,
            'orderType' => $this->_orderType
        ];
        $etmOrder = $this->_etmOrderFactory->create($params);

        return [
            $etmOrder->getType(),
            $etmOrder->getFirstName(),
            $etmOrder->getLastName(),
            $etmOrder->getAddress(),
            $etmOrder->getPostalCode(),
            $etmOrder->getCity(),
            $etmOrder->getCountry(),
            $etmOrder->getSSN(),
            $etmOrder->getDateOfBirth(),
            $etmOrder->getGender(),
            $etmOrder->getPhone(),
            $etmOrder->getEmail(),
            $etmOrder->getMailAdvertisement(),
            $etmOrder->getTM(),
            $etmOrder->getEmailInformation(),
            $etmOrder->getEmailAdvertisement(),
            $etmOrder->getSMSInformation(),
            $etmOrder->getSMSAdvertisement(),
            $etmOrder->getApprovedAddressSale(),
            $etmOrder->getCampaignCode(),
            $etmOrder->getSellerCompany(),
            $etmOrder->getIPNo(),
            $this->_dateHelper->format($etmOrder->getOrderDate(), 'Y-m-d'),
            $etmOrder->getOrderNumberExternal(),
            $etmOrder->getFreight(),
            $etmOrder->getPaymentFee(),
            $etmOrder->getWayOfPayment(),
            $etmOrder->getWayOfDelivery(),
            $etmOrder->getTermsOfDelivery(),
            $etmOrder->getTermsOfPayment(),
            //$etmOrder->getPaymentServiceProvider(),
            //$this->$dateHelper->format($etmOrder->getDeliveryDate()),
        ];
    }

    /**
     * Get Order Items
     * 
     * @param Magento\Sales\Model\Order $order
     * 
     * @return Array $orderitems
     */
    public function getOrderItems($order)
    {
        # code...
        $orderItems = [];
        $allOrderItems = $order->getAllItems();

        foreach($allOrderItems as $orderItem) {
            // 
            $item = [];
            
            /**
             * @Todo maybe offload the loading of product info to another class
             * 
             * It is necessary to Load the product by ID 
             * using the product repository
             * The Product object from the order ($orderItem)
             * does not seem to be able to retrieve custom attributes
             */
            $product = $this->_productRepository->getById($orderItem->getProductId());
            
            $item[] = $product->getArticlenumber();
            $item[] = $product->getItemSize();
            $item[] = $product->getItemColor();
            $item[] = $orderItem->getQtyOrdered();

            $orderItems[] = array_map([$this, 'encodeSpecialCharacters'], $item);
        }

        return $orderItems;
    }

    public function encodeSpecialCharacters($string)
    {
        # code...
        return iconv(mb_detect_encoding($string), 'Windows-1252//TRANSLIT', $string);
    }


}