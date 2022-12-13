<?php

namespace Dtn\Campaign\Controller\Adminhtml\Items;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;
use Dtn\Campaign\Model\ResourceModel\Campaign\CollectionFactory;
use Magento\Backend\App\Action;
use Dtn\Campaign\Helper\Campaign as HelperCampaign;

class MassDisable extends Action
{
    /**
     * @var HelperCampaign
     */
    protected $helperCampaign;
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
        HelperCampaign    $helperCampaign
    )
    {
        $this->helperCampaign = $helperCampaign;
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $status = false;
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $collectionSize = $collection->getSize();
        foreach ($collection as $record) {
            // set status campaign
            $record->setEnable(true);
            $record->setStatus(0);
            $record->save();
            // disable page
            $this->helperCampaign->updateStatusPage($record->getId(), $status);
            // enable block
            $this->helperCampaign->updateStatusBlock($record->getId(), $status);
            // enable Product
            $this->helperCampaign->updateStatusProduct($record->getId(), $status);
        }
        $this->messageManager->addSuccess(__('A total of %1 record(s) have been Enable ', $collectionSize));
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}
