<?php

namespace Prymag\OrdersExporter\Model\CSV\ETM;

use Magento\Sales\Model\Order;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

use Prymag\OrdersExporter\Model\CSV\ETM\OrderTraits\AddressTrait;
use Prymag\OrdersExporter\Model\CSV\ETM\OrderTraits\CustomerPermissionTrait;
use Prymag\OrdersExporter\Model\CSV\ETM\OrderTraits\CustomerTrait;
use Prymag\OrdersExporter\Model\CSV\ETM\OrderTraits\DeliveryTrait;
use Prymag\OrdersExporter\Model\CSV\ETM\OrderTraits\PaymentTrait;
use Prymag\OrdersExporter\Model\CSV\ETM\OrderTraits\OrderInfoTrait;

class ETMOrder {

    use AddressTrait;
    use CustomerPermissionTrait;
    use CustomerTrait;
    use DeliveryTrait;
    use PaymentTrait;
    use OrderInfoTrait;

    protected $_orderType = '';
    protected $_order;
    protected $_scopeConfig;

    public function __construct(
        $orderType,
        Order $order,
        ScopeConfigInterface $scopeConfig
    ) {
        # code...
        $this->_orderType = $orderType;
        $this->_order = $order;
        $this->_scopeConfig = $scopeConfig;

    }

    public function getLanguage() 
    {
        //
        $value = 'general/locale/code';
        $store = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        $storeId = $this->_order->getStoreId();
        $storeCode = $this->_scopeConfig
            ->getValue($value, $store, $storeId);
        
        $exploded = explode('_', $storeCode);

        return $exploded[0];
        //return $this->_order-;
    }

    public function getType()
    {
        # code...
        return $this->_orderType;
    }

    // @Todo
    public function getSSN()
    {
        # code...
        return '';
    }

    public function getCampaignCode()
    {
        # code...
        $code = $this->_order
            ->getStore()
            ->getWebsite()
            ->getCode();

        switch($code) {
            case 'almea':
                return 'AL';
            case 'caredirect_se';
                return 'CDSE';
            case 'halsorutan_se';
                return 'HALSSE';
            default:
                return 'EMP';
        }
    }

    public function getSellerCompany()
    {
        # code...
        return 'web';
    } 

}