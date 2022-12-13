<?php

namespace Dtn\Campaign\Model\ResourceModel\CampaignPage;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'campaign_page_id';

    protected function _construct()
    {
        $this->_init(
            'Dtn\Campaign\Model\CampaignPage',
            'Dtn\Campaign\Model\ResourceModel\CampaignPage'
        );
    }
}
