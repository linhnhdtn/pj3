<?php

namespace Linh\Sales\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Sales\Api\Data\ShipmentInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Sales\Api\ShipmentRepositoryInterface;

class Order extends AbstractHelper
{
    protected $shipmentRepository;

    protected $searchCriteriaBuilder;

    public function __construct
    (
        Context                     $context,
        ShipmentRepositoryInterface $shipmentRepository,
        SearchCriteriaBuilder       $searchCriteriaBuilder
    )
    {

        $this->shipmentRepository = $shipmentRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        parent::__construct($context);
    }

    public function getShipmentCode($orderId)
    {
        $shipmentCode = null;
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('order_id', $orderId)->create();
        try {
            $shipments = $this->shipmentRepository->getList($searchCriteria);
            $shipmentRecords = $shipments->getFirstItem();
            $shipmentCode = $shipmentRecords->getShipCode();
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
        return $shipmentCode;
    }
}
