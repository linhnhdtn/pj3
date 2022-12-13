<?php

namespace Dtn\Campaign\Ui\Campaign;

use Dtn\Campaign\Model\ResourceModel\Campaign\Collection as CampaignCollection;
use Dtn\Campaign\Model\ResourceModel\CampaignBlock\CollectionFactory as CampaignBlockCollection;
use Dtn\Campaign\Model\ResourceModel\CampaignPage\CollectionFactory as CampaignPageCollection;
use Dtn\Campaign\Model\ResourceModel\CampaignProduct\CollectionFactory as CampaignProductCollection;
use Magento\Ui\DataProvider\AbstractDataProvider;

class DataProvider extends AbstractDataProvider
{
    /**
     * @var CampaignProductCollection
     */
    protected $productCollection;
    /**
     * @var CampaignBlockCollection
     */
    protected $blockCollection;
    /**
     * @var CampaignPageCollection
     */
    protected $pageCollection;

    protected $_loadedData;
    protected $collection;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CampaignCollection $campaignCollection,
        CampaignBlockCollection $blockCollection,
        CampaignPageCollection $pageCollection,
        CampaignProductCollection $productCollection,
        array $meta = [],
        array $data = []
    )
    {
        $this->productCollection = $productCollection;
        $this->blockCollection = $blockCollection;
        $this->pageCollection = $pageCollection;
        $this->collection = $campaignCollection;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * @return array
     */
    public function getData()
    {
        if (isset($this->_loadedData)) {
            return $this->_loadedData;
        }
        $items = $this->collection->getItems();
        foreach ($items as $item) {
            $data = [];
            $data['campaign'] = $item->getData();
            $data['page_modal'] = $this->getPage($item->getData()["campaign_id"]);
            $data['block_modal'] = $this->getBlock($item->getData()["campaign_id"]);
            $data['product'] = $this->getProduct($item->getData()["campaign_id"]);
            $this->_loadedData[$item->getId()] = $data;
        }
        return $this->_loadedData;
    }

    /**
     * @param $campaignId
     * @return array
     */
    public function getBlock($campaignId)
    {
        $collectionBlock = $this->blockCollection->create()->addFieldToFilter("campaign_id", ["eq" => $campaignId])->load();

        $arrBlockId["data"]["links"]["block_modal"] = [];
        foreach ($collectionBlock as $block) {
            $arrBlockId["data"]["links"]["block_modal"][] = [
                'id' => $block->getCmsBlockId(),
                'title' => $block->getTitle( )
            ];
        }
        return $arrBlockId;

    }

    /**
     * @param $campaignId
     * @return array
     */
    public function getPage($campaignId)
    {
        $collectionPage = $this->pageCollection->create()->addFieldToFilter("campaign_id", ["eq" => $campaignId])->load();
        $arrPageId["data"]["links"]["page_modal"] = [];
        foreach ($collectionPage as $page) {
            $arrPageId["data"]["links"]["page_modal"][] = [
                'id' => $page->getCmsPageId(),
                'title' => $page->getTitle()
            ];

        }
        return $arrPageId;

    }

    /**
     * @param $campaignId
     * @return array
     */
    public function getProduct($campaignId)
    {
        $collectionProduct = $this->productCollection->create()->addFieldToFilter("campaign_id", ["eq" => $campaignId])->load();
        $arrProduct["data"]["links"]["product"] = [];
        foreach ($collectionProduct as $product) {
            $arrProduct["data"]["links"]["product"][] = [
                'id' => $product->getProductId(),
                'name' => $product->getProductName(),
                'sku' => $product->getProductSku()
            ];
        }

        return $arrProduct;
    }
}
