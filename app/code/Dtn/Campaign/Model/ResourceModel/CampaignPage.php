<?php

namespace Dtn\Campaign\Model\ResourceModel;

class CampaignPage extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    protected function _construct()
    {
        $this->_init('campaign_page', 'campaign_page_id');
    }
}
