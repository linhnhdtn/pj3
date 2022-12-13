<?php

namespace Dtn\Campaign\Model\ResourceModel;

class Campaign extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init('campaign', 'campaign_id');
    }
}
