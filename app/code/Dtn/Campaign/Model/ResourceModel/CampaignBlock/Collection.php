<?php

namespace Dtn\Campaign\Model\ResourceModel\CampaignBlock;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'campaign_block_id';

    protected function _construct()
    {
        $this->_init(
            'Dtn\Campaign\Model\CampaignBlock',
            'Dtn\Campaign\Model\ResourceModel\CampaignBlock'
        );
    }
}
