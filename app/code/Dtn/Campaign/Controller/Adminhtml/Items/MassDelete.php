<?php

namespace Dtn\Campaign\Controller\Adminhtml\Items;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;
use Dtn\Campaign\Model\ResourceModel\Campaign\CollectionFactory;
use Dtn\Campaign\Helper\Campaign;

class MassDelete extends \Magento\Backend\App\Action
{
    /**
     * @var Campaign
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

    /**
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param Campaign $helperCampaign
     */
    public function __construct(
        Context           $context,
        Filter            $filter,
        CollectionFactory $collectionFactory,
        Campaign          $helperCampaign

    )
    {
        $this->helperCampaign = $helperCampaign;
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context);
    }


    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $collectionSize = $collection->getSize();
        foreach ($collection as $record) {
            // delete campaign
            $record->delete();
            // delete block
            $this->helperCampaign->deleteCampaignBlockByCampaignId($record->getCampaignId());
            //delete page
            $this->helperCampaign->deleteCampaignPageByCampaignId($record->getCampaignId());
            //delete product
            $this->helperCampaign->deleteCampaignProductByCampaignId($record->getCampaignId());
        }
        $this->messageManager->addSuccess(__('A total of %1 record(s) have been deleted.', $collectionSize));
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}
