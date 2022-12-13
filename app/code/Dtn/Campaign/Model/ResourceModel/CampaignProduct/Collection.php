<?php

namespace Dtn\Campaign\Model\ResourceModel\CampaignProduct;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'campaign_product_id';

    protected function _construct()
    {
        $this->_init(
            'Dtn\Campaign\Model\CampaignProduct',
            'Dtn\Campaign\Model\ResourceModel\CampaignProduct'
        );
    }
}
