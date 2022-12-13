<?php

namespace Dtn\Campaign\Controller\Adminhtml\Items;

use Dtn\Campaign\Controller\Adminhtml\Items;
use Dtn\Campaign\Model\CampaignFactory;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\Image\AdapterFactory;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magento\MediaStorage\Model\File\UploaderFactory;

class Delete extends Items
{
    /**
     * @var CampaignFactory
     */
    protected $campaignProduct;

    /**
     * @param Context $context
     * @param Registry $coreRegistry
     * @param ForwardFactory $resultForwardFactory
     * @param PageFactory $resultPageFactory
     * @param DirectoryList $directoryList
     * @param UploaderFactory $uploaderFactory
     * @param AdapterFactory $adapterFactory
     * @param Filesystem $filesystem
     * @param File $file
     * @param CampaignFactory $campaignProduct
     */
    public function __construct(
        Context         $context,
        Registry        $coreRegistry,
        ForwardFactory  $resultForwardFactory,
        PageFactory     $resultPageFactory,
        DirectoryList   $directoryList,
        UploaderFactory $uploaderFactory,
        AdapterFactory  $adapterFactory,
        Filesystem      $filesystem,
        File            $file,
        CampaignFactory $campaignProduct
    )
    {
        $this->campaignProduct = $campaignProduct;
        parent::__construct(
            $context,
            $coreRegistry,
            $resultForwardFactory,
            $resultPageFactory,
            $directoryList,
            $uploaderFactory,
            $adapterFactory,
            $filesystem,
            $file
        );
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        if ($id) {
            try {
                $model = $this->campaignProduct->create();
                $model->load($id);
                $model->delete();
                $this->messageManager->addSuccess(__('You deleted the item.'));
                $this->_redirect('dtn_campaign/*/');
                return;
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addError(
                    __('We can\'t delete item right now. Please review the log and try again.')
                );
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
                $this->_redirect('dtn_campaign/*/edit', ['id' => $this->getRequest()->getParam('id')]);
                return;
            }
        }
        $this->messageManager->addError(__('We can\'t find a item to delete.'));
        $this->_redirect('dtn_campaign/*/');
    }
}
