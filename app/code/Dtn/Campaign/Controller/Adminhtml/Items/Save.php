<?php

namespace Dtn\Campaign\Controller\Adminhtml\Items;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\Image\AdapterFactory;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\Registry;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\App\Action\Context;
use Dtn\Campaign\Model\CampaignPageFactory;
use Dtn\Campaign\Model\CampaignProductFactory;
use Dtn\Campaign\Model\CampaignBlockFactory;
use Dtn\Campaign\Controller\Adminhtml\Items;
use Dtn\Campaign\Model\CampaignFactory;
use Dtn\Campaign\Helper\Campaign as HelperCampaign;
use Magento\Backend\Model\Session;

class Save extends Items
{
    /**
     * @var Session
     */
    protected $_session;
    /**
     * @var HelperCampaign
     */
    protected $helperCampaign;
    /**
     * @var CampaignFactory
     */
    protected $campaignFactory;
    /**
     * @var CampaignPageFactory
     */
    protected $campaignPage;
    /**
     * @var CampaignBlockFactory
     */
    protected $campaignBlock;
    /**
     * @var CampaignProductFactory
     */
    protected $campaignProduct;

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
        CampaignBlockFactory   $campaignBlock,
        CampaignPageFactory    $campaignPage,
        CampaignProductFactory $campaignProduct,
        CampaignFactory        $campaignFactory,
        HelperCampaign         $helperCampaign,
        Session                $_session
    )
    {
        $this->_session = $_session;
        $this->helperCampaign = $helperCampaign;
        $this->campaignFactory = $campaignFactory;
        $this->campaignBlock = $campaignBlock;
        $this->campaignPage = $campaignPage;
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
        if ($this->getRequest()->getPostValue()) {
            try {
                $campaign = $this->campaignFactory->create();
                $data = $this->getRequest()->getPostValue();
                $id = $this->getRequest()->getParam('id');
                if ($id) {
                    $campaign->load($id);
                    if ($id != $campaign->getId()) {
                        throw new \Magento\Framework\Exception\LocalizedException(__('The wrong item is specified.'));
                    }
                }
                $campaign->setData($data["campaign"]);
                $campaign->setEnable(false);
                $this->_session->setPageData($campaign->getData("campaign"));
                $newCampaign = $campaign->save();
                $idCampaign = $newCampaign->getCampaignId();

                // page
                if (!empty($data["page_modal"]["data"]["links"]["page_modal"])) {
                    $this->helperCampaign->deleteCampaignPageByCampaignId($idCampaign);
                    foreach ($data["page_modal"]["data"]["links"]["page_modal"] as $page) {
                        $pageFactory = $this->campaignPage->create();
                        $pageFactory->setCmsPageId($page["id"]);
                        $pageFactory->setTitle($page["title"]);
                        $pageFactory->setCampaignId($idCampaign);
                        $pageFactory->save();
                    }
                } else {
                    $this->helperCampaign->deleteCampaignPageByCampaignId($idCampaign);
                }

                //block
                if (!empty($data["block_modal"]["data"]["links"]["block_modal"])) {
                    $this->helperCampaign->deleteCampaignBlockByCampaignId($idCampaign);
                    foreach ($data["block_modal"]["data"]["links"]["block_modal"] as $block) {
                        $blockFactory = $this->campaignBlock->create();
                        $blockFactory->setCmsBlockId($block["id"]);
                        $blockFactory->setTitle($block["title"]);
                        $blockFactory->setCampaignId($idCampaign);
                        $blockFactory->save();
                    }
                } else {
                    $this->helperCampaign->deleteCampaignBlockByCampaignId($idCampaign);
                }

                // product
                if (!empty($data["product"]["data"]["links"]["product"])) {
                    $this->helperCampaign->deleteCampaignProductByCampaignId($idCampaign);
                    foreach ($data["product"]["data"]["links"]["product"] as $product) {
                        $productFactory = $this->campaignProduct->create();
                        $productFactory->setProductId($product["id"]);
                        $productFactory->setProductSku($product["sku"]);
                        $productFactory->setProductName($product["name"]);
                        $productFactory->setCampaignId($idCampaign);
                        $productFactory->save();
                    }
                } else {
                    $this->helperCampaign->deleteCampaignProductByCampaignId($idCampaign);
                }

                $this->messageManager->addSuccess(__('You saved the campaign.'));
                $this->_session->setPageData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('dtn_campaign/*/edit', ['id' => $campaign->getId()]);
                    return;
                }
                $this->_redirect('dtn_campaign/*/');
                return;
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
                $id = (int)$this->getRequest()->getParam('id');
                if (!empty($id)) {
                    $this->_redirect('dtn_campaign/*/edit', ['id' => $id]);
                } else {
                    $this->_redirect('dtn_campaign/*/new');
                }
                return;
            } catch (\Exception $e) {
                $this->messageManager->addError(
                    __('Something went wrong while saving the item data. Please review the error log.')
                );
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
                $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData($data);
                $this->_redirect('dtn_campaign/*/edit', ['id' => $this->getRequest()->getParam('id')]);
                return;
            }
        }
        $this->_redirect('dtn_campaign/*/');
    }
}
