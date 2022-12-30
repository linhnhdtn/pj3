<?php

namespace Dtn\Email\Cron;

use Dtn\Email\Helper\Email as HelperEmail;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;

class Process
{
    /**
     * @var HelperEmail
     */
    protected $helperEmail;

    /**
     * @var CollectionFactory
     */
    protected $orderCollectionFactory;

    public function __construct
    (
        HelperEmail       $helperEmail,
        CollectionFactory $orderCollectionFactory
    )
    {
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->helperEmail = $helperEmail;
    }

    public function execute()
    {
        $jobSendEmails = $this->helperEmail->getCollectionJobEmailNotify();
        foreach ($jobSendEmails as $job) {

            // get collection order
            $collectionOrder = $this->orderCollectionFactory->create()
                ->addAttributeToSelect('*')
                ->addFieldToFilter('status', ['eq' => $job->getStatus()])
                ->addFieldToFilter('created_at',
                    ['gteq' => $job->getStartDate()]
                )
                ->addFieldToFilter('created_at',
                    ['lteq' => $job->getEndDate()]
                )
                ->load();

            // get array sku err in config
            $productMiss = $this->helperEmail->getCollectionProductMiss($job->getId());
            $skus = [];
            foreach ($productMiss as $product) {
                $skus[] = $product["product_sku"];
            }

            // get sku err in order and send email notify
            $skuErr = [];
            foreach ($collectionOrder as $order) {
                $items = $order->getAllitems();
                foreach ($items as $item) {
                    if (in_array($item->getSku(), $skus)) {
                        $skuErr[] = $item->getSku();
                    }
                }
                if (count($skuErr) > 0) {
                    $this->helperEmail->sendEmail($order->getIncrementId(), $skuErr, $order->getCustomerEmail(), $job->getId(), $order->getStoreId());
                }
            }
        }
     }
}
