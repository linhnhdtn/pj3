<?php

namespace Linh\Sales\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\ResourceModel\Grid;
use Magento\Framework\App\ResourceConnection;

class ShipCode implements ObserverInterface
{
    /**
     * @var ResourceConnection
     */
    protected $connection;
    /**
     * @var Grid
     */
    protected $salesGrid;

    public function __construct
    (
        Grid $salesGrid,
        ResourceConnection $connection
    )
    {
        $this->connection = $connection;
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
            $sql = "UPDATE sales_shipment_grid SET ship_code = ('" . $shipCode . "') WHERE `entity_id` =" . $shipment->getId();
            $this->connection->getConnection()->query($sql);
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
