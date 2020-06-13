<?php

namespace Prymag\OrdersExporter\Model;

use Magento\Customer\Model\ResourceModel\GroupRepository;
use Magento\Sales\Model\Order;

class OrderCustomerGroup {
    //
    protected $_groupRepository;

    public function __construct( 
        GroupRepository $groupRepository
    ) {
        # code...
        $this->_groupRepository = $groupRepository;
    }
    /**
     * Return name of the customer group.
     *
     * @return string
     */
    public function getCustomerGroupName(Order $order)
    {
        if (!$order) {
            return '';
        }

        try {
            $customerGroupId = $order->getCustomerGroupId();
            if ($customerGroupId !== null) {
                return $this->_groupRepository->getById($customerGroupId)->getCode();
            }
        } catch (NoSuchEntityException $e) {
            return '';
        }
    }
}