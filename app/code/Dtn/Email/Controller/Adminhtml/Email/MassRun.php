<?php

namespace Dtn\Email\Controller\Adminhtml\Email;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;
use Dtn\Email\Model\ResourceModel\EmailNotify\CollectionFactory;
use Dtn\Email\Helper\Email as HelperEmail;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as CollectionOrder;

class MassRun extends \Magento\Backend\App\Action
{
    protected $orderCollectionFactory;
    /**
     * @var HelperEmail
     */
    protected $helperEmail;

    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    public function __construct(
        Context           $context,
        Filter            $filter,
        CollectionFactory $collectionFactory,
        HelperEmail       $helperEmail,
        CollectionOrder   $orderCollectionFactory

    )
    {
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->helperEmail = $helperEmail;
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $jobSendEmails = $this->filter->getCollection($this->collectionFactory->create());
        foreach ($jobSendEmails as $job) {
            if ($job->getEnable()) {
                // get array sku err in config
                $productMiss = $this->helperEmail->getCollectionProductMiss($job->getId());
                $skus = [];
                foreach ($productMiss as $product) {
                    $skus[] = $product["product_sku"];
                }

                $jobStatusArr = explode(",", $job->getStatus());
                foreach ($jobStatusArr as $jobStatus) {
                    $collectionOrder = $this->orderCollectionFactory->create()
                        ->addAttributeToSelect('*')
                        ->addFieldToFilter('status', ['eq' => $jobStatus])
                        ->addFieldToFilter('created_at',
                            ['gteq' => $job->getStartDate()]
                        )
                        ->addFieldToFilter('created_at',
                            ['lteq' => $job->getEndDate()]
                        )
                        ->load();

                    // get sku err in order and send email notify
                    $skuMiss = [];
                    foreach ($collectionOrder as $order) {
                        $items = $order->getAllitems();
                        foreach ($items as $item) {
                            if (in_array($item->getSku(), $skus)) {
                                $skuMiss[] = [
                                    'sku' => $item->getSku(),
                                    'name' => $item->getName(),
                                    'qty' => $item->getQtyOrdered()
                                ];
                            }
                        }
                        if (count($skuMiss) > 0) {
                            $this->helperEmail->sendEmail($order->getIncrementId(), $skuMiss, $order->getCustomerEmail(), $job->getId(), $order->getStoreId());
                            $skuMiss = [];
                        }
                    }
                }
                $this->messageManager->addSuccess(__('Run success'));
            } else {
                $this->messageManager->addError(__('Job is Disable'));
            }
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            return $resultRedirect->setPath('*/*/');
        }
    }
}
