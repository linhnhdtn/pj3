<?php

namespace Dtn\Campaign\Model\ResourceModel;

class CampaignProduct extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('campaign_product', 'campaign_product_id');
    }
}
