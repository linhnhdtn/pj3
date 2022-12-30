<?php

namespace Dtn\Email\Controller\Adminhtml\Email;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;
use Dtn\Email\Model\ResourceModel\EmailNotify\CollectionFactory;
use Dtn\Email\Helper\Email as HelperEmail;

class MassDelete extends \Magento\Backend\App\Action
{
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
        HelperEmail       $helperEmail

    )
    {
        $this->helperEmail = $helperEmail;
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
            $this->helperEmail->deleteProductMiss($record->getId());
        }
        $this->messageManager->addSuccess(__('A total of %1 record(s) have been deleted.', $collectionSize));
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}
