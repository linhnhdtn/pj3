<?php

namespace Dtn\Campaign\Controller\Adminhtml\Items;

use Magento\Backend\App\Action;
use Dtn\Campaign\Model\CampaignFactory;
use Magento\Backend\App\Action\Context;
use Magento\Cms\Model\BlockFactory;
use Magento\Cms\Model\PageFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Dtn\Campaign\Helper\Campaign;

class Schedule extends Action
{
    const PROCESSING = 2;

    /**
     * @var Campaign
     */
    protected $helperCampaign;

    /**
     * @var PageFactory
     */
    protected $pageFactory;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var BlockFactory
     */
    protected $blockFactory;
    /**
     * @var CampaignFactory
     */
    protected $campaignFactory;

    public function __construct(
        Context                    $context,
        CampaignFactory            $campaignFactory,
        BlockFactory               $blockFactory,
        ProductRepositoryInterface $productRepository,
        PageFactory                $pageFactory,
        Campaign                   $helperCampaign
    )
    {
        $this->helperCampaign = $helperCampaign;
        $this->pageFactory = $pageFactory;
        $this->productRepository = $productRepository;
        $this->blockFactory = $blockFactory;
        $this->campaignFactory = $campaignFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $idSchedule = $this->getRequest()->getParam('id');
        $campaign = $this->campaignFactory->create();
        if ($idSchedule) {
            $campaign->load($idSchedule);
            if (!$campaign->getId()) {
                $this->messageManager->addError(__('This campaign no longer exists.'));
                $this->_redirect('dtn_campaign/*');
                return;
            }
            try {
                $campaign->setStatus(self::PROCESSING);
                $campaign->save();

                $this->messageManager->addSuccessMessage(__('Job ' . $campaign->getNameCapaign() . ' correctly scheduled'));
            } catch (\Exception $exception) {
                echo $exception->getMessage();
            }
        }
        $this->_view->getLayout()->getBlock('items_items_edit');
        $this->_view->renderLayout();
        $this->_redirect('dtn_campaign/*/');
    }
}
