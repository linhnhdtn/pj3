<?php

namespace Dtn\Campaign\Model\ResourceModel;

class CampaignBlock extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('campaign_block', 'campaign_block_id');
    }
}
