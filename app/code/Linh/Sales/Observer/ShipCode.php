<?php

namespace Linh\Sales\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\ResourceModel\Grid;

class ShipCode implements ObserverInterface
{
    protected $salesGrid;

    public function __construct
    (
        Grid $salesGrid
    )
    {
        $this->salesGrid = $salesGrid;
    }

    public function execute(Observer $observer)
    {
        $shipment = $observer->getData("shipment");
        $shipCode = $shipment->getShipCode();
        if (!$shipCode) {
            // save ship_code
            $shipCode = $this->generateRandomCode();
            $shipment->setShipCode($shipCode);
            $shipment->save();
        }
    }

    function generateRandomCode()
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $code = '';
        $length = 10;

        $max = strlen($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $randomIndex = mt_rand(0, $max);
            $code .= $characters[$randomIndex];
        }

        return $code;
    }

}
