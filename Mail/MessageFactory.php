<?php
/**
 * https://meetanshi.com/blog/add-attachments-with-email-in-magento-2-3-x/
 */
namespace Prymag\OrdersExporter\Mail;

class MessageFactory extends \Magento\Framework\Mail\MessageInterfaceFactory
{

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;
 
    /**
     * Factory constructor
     *
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param string $instanceName
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager, 
        $instanceName = '\\Prymag\\OrdersExporter\\Mail\\Message'
    )
    {
        $this->_objectManager = $objectManager;
        $this->_instanceName = $instanceName;
    }

    /**
     * Create class instance with specified parameters
     *
     * @param array $data
     * @return \Magento\Framework\Mail\MessageInterface
     */
    public function create(array $data = [])
    {
        return $this->_objectManager->create($this->_instanceName, $data);
    }

}