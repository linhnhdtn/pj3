<?php

namespace Dtn\Email\Ui\Email;

use Dtn\Email\Model\ResourceModel\EmailNotify\CollectionFactory;
use Dtn\Email\Model\ResourceModel\Product\CollectionFactory as ProductMissCollection;
class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    protected $productMissCollection;
    protected $_loadedData;
    /**
     * @var Collection
     */
    protected $collection;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        ProductMissCollection $productMissCollection,
        array $meta = [],
        array $data = []
    )
    {
        $this->productMissCollection = $productMissCollection;
        $this->collection = $collectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData()
    {
        if (isset($this->_loadedData)) {
            return $this->_loadedData;
        }
        $items = $this->collection->getItems();
        foreach ($items as $item) {
            $data = [];
            $data["email"] =  $item->getData();
            $data['product'] = $this->getProduct($item->getData()["id"]);
            $this->_loadedData[$item->getId()] = $data;
        }
        return $this->_loadedData;
    }

    public function getProduct($jobId)
    {
        $collectionProduct = $this->productMissCollection->create()->addFieldToFilter("email_notify_id", ["eq" => $jobId])->load();
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
