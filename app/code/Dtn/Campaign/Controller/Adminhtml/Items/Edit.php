<?php

namespace Dtn\Campaign\Controller\Adminhtml\Items;

use Dtn\Campaign\Controller\Adminhtml\Items;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\Image\AdapterFactory;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Framework\View\Result\PageFactory;
use Dtn\Campaign\Model\CampaignFactory;
use Dtn\Campaign\Model\ResourceModel\Campaign\Collection as CampaignCollection;
use Magento\Backend\Model\Session;

class Edit extends Items
{
    /**
     * @var Session
     */
    protected $session;

    /**
     * @var CampaignFactory
     */
    protected $campaignFactory;

    /**
     * @var CampaignCollection
     */
    protected $campaignCollection;

    /**
     * @param Context $context
     * @param Registry $coreRegistry
     * @param ForwardFactory $resultForwardFactory
     * @param PageFactory $resultPageFactory
     * @param DirectoryList $directoryList
     * @param UploaderFactory $uploaderFactory
     * @param AdapterFactory $adapterFactory
     * @param Filesystem $filesystem
     * @param Filesystem\Driver\File $file
     * @param CampaignFactory $campaignFactory
     * @param Session $session
     * @param CampaignCollection $campaignCollection
     */
    public function __construct(
        Context                $context,
        Registry               $coreRegistry,
        ForwardFactory         $resultForwardFactory,
        PageFactory            $resultPageFactory,
        DirectoryList          $directoryList,
        UploaderFactory        $uploaderFactory,
        AdapterFactory         $adapterFactory,
        Filesystem             $filesystem,
        Filesystem\Driver\File $file,
        CampaignFactory        $campaignFactory,
        Session                $session,
        CampaignCollection     $campaignCollection
    )
    {
        $this->campaignCollection = $campaignCollection;
        $this->session = $session;
        $this->campaignFactory = $campaignFactory;
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
        $campaignFactory = $this->campaignFactory->create();
        if ($id) {
            $campaignFactory->load($id);
            if (!$campaignFactory->getId()) {
                $this->messageManager->addError(__('This item no longer exists.'));
                $this->_redirect('dtn_campaign/*');
                return;
            }
        }

        // set entered data if was error when we do save
        $data = $this->session->getPageData(true);
        if (!empty($data)) {
            $campaignFactory->addData($data);
        }

        $camaignCollection = $this->campaignCollection->load();
        $this->_coreRegistry->register('current_dtn_campaign', $camaignCollection);
        $this->_initAction();
        $this->_view->getLayout()->getBlock('items_items_edit');
        $this->_view->renderLayout();
    }
}
