<?php

namespace Dtn\Campaign\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Dtn\Campaign\Model\ResourceModel\CampaignBlock\CollectionFactory as CampaignBlockCollection;
use Dtn\Campaign\Model\ResourceModel\CampaignPage\CollectionFactory as CampaignPageCollection;
use Dtn\Campaign\Model\ResourceModel\CampaignProduct\CollectionFactory as CampaignProductCollection;
use Dtn\Campaign\Model\CampaignPageFactory;
use Dtn\Campaign\Model\CampaignProductFactory;
use Dtn\Campaign\Model\CampaignBlockFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Cms\Model\BlockFactory;
use Magento\Cms\Model\PageFactory;

class Campaign extends AbstractHelper
{
    /**
     * @var PageFactory
     */
    protected $pageFactory;

    /**
     * @var BlockFactory
     */
    protected $blockFactory;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;
    /**
     * @var CampaignBlockFactory
     */
    protected $campaignBlock;

    /**
     * @var CampaignProductFactory
     */
    protected $campaignProduct;

    /**
     * @var CampaignPageFactory
     */
    protected $campaignPage;

    /**
     * @var CampaignProductCollection
     */
    protected $campaignProductCollection;

    /**
     * @var CampaignPageCollection
     */
    protected $campaignPageCollection;

    /**
     * @var CampaignBlockCollection
     */
    protected $campaignBlockCollection;


    public function __construct(
        Context                    $context,
        CampaignBlockCollection    $campaignBlockCollection,
        CampaignPageCollection     $campaignPageCollection,
        CampaignProductCollection  $campaignProductCollection,
        CampaignPageFactory        $campaignPage,
        CampaignBlockFactory       $campaignBlock,
        CampaignProductFactory     $campaignProduct,
        ProductRepositoryInterface $productRepository,
        BlockFactory               $blockFactory,
        PageFactory                $pageFactory
    )
    {
        $this->blockFactory = $blockFactory;
        $this->pageFactory = $pageFactory;
        $this->productRepository = $productRepository;
        $this->campaignProduct = $campaignProduct;
        $this->campaignPage = $campaignPage;
        $this->campaignBlock = $campaignBlock;
        $this->campaignBlockCollection = $campaignBlockCollection;
        $this->campaignPageCollection = $campaignPageCollection;
        $this->campaignProductCollection = $campaignProductCollection;
        parent::__construct($context);
    }

    /**
     * @param $campaignId
     * @return mixed
     */
    public function getCollectionCampaignProductByIdCampaign($campaignId)
    {
        $collectionProduct = $this->campaignProductCollection->create()
            ->addFieldToFilter("campaign_id", ["eq" => $campaignId])
            ->load();
        return $collectionProduct;
    }

    /**
     * @param $campaignId
     * @return mixed
     */
    public function getCollectionCampaignPageByIdCampaign($campaignId)
    {
        $collectionPage = $this->campaignPageCollection->create()
            ->addFieldToFilter("campaign_id", ["eq" => $campaignId])
            ->load();
        return $collectionPage;
    }

    /**
     * @param $campaignId
     * @return mixed
     */
    public function getCollectionCampaignBlockByIdCampaign($campaignId)
    {
        $collectionBlock = $this->campaignBlockCollection->create()
            ->addFieldToFilter("campaign_id", ["eq" => $campaignId])
            ->load();
        return $collectionBlock;
    }

    /**
     * @param $campaignId
     * @return void
     */
    public function deleteCampaignProductByCampaignId($campaignId)
    {
        $productCampaigns = $this->getCollectionCampaignProductByIdCampaign($campaignId);
        foreach ($productCampaigns as $productCampaign) {
            $this->campaignProduct->create()->load($productCampaign->getCampaignProductId())->delete();
        }
    }

    /**
     * @param $campaignId
     * @return void
     */
    public function deleteCampaignBlockByCampaignId($campaignId)
    {
        $blockCampaigns = $this->getCollectionCampaignBlockByIdCampaign($campaignId);
        foreach ($blockCampaigns as $blockCampaign) {
            $this->campaignBlock->create()->load($blockCampaign->getCampaignBlockId())->delete();
        }
    }

    /**
     * @param $campaignId
     * @return void
     */
    public function deleteCampaignPageByCampaignId($campaignId)
    {
        $pageCampaigns = $this->getCollectionCampaignPageByIdCampaign($campaignId);
        foreach ($pageCampaigns as $pageCampaign) {
            $this->campaignPage->create()->load($pageCampaign->getCampaignPageId())->delete();
        }
    }


    /**
     * @param $campaignId
     * @param $status
     * @return void
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\StateException
     */
    public function updateStatusProduct($campaignId, $status)
    {
        $productCampaigns = $this->getCollectionCampaignProductByIdCampaign($campaignId);
        foreach ($productCampaigns as $productCampaign) {
            $productRepository = $this->productRepository->getById($productCampaign->getProductId());
            if ($status) {
                $productRepository->setStatus(Status::STATUS_ENABLED);
            } else {
                $productRepository->setStatus(Status::STATUS_DISABLED);
            }
            $this->productRepository->save($productRepository);
        }
    }

    /**
     * @param $campaignId
     * @param $status
     * @return void
     * @throws \Exception
     */
    public function updateStatusPage($campaignId, $status)
    {
        $pageCampaigns = $this->getCollectionCampaignPageByIdCampaign($campaignId);
        foreach ($pageCampaigns as $pageCampaign) {
            $pageFactory = $this->pageFactory->create()->load($pageCampaign->getCmsPageId());
            if ($status) {
                $pageFactory->setIsActive(true);
            } else {
                $pageFactory->setIsActive(false);
            }
            $pageFactory->save();
        }
    }

    /**
     * @param $campaignId
     * @param $status
     * @return void
     * @throws \Exception
     */
    public function updateStatusBlock($campaignId, $status)
    {
        $blockCampaigns = $this->getCollectionCampaignBlockByIdCampaign($campaignId);
        foreach ($blockCampaigns as $blockCampaign) {
            $blockFactory = $this->blockFactory->create()->load($blockCampaign->getCmsBlockId());
            if ($status) {
                $blockFactory->setIsActive(true);
            } else {
                $blockFactory->setIsActive(false);
            }
            $blockFactory->save();
        }
    }
}
