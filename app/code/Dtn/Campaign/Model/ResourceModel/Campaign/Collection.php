<?php

namespace Dtn\Campaign\Model\ResourceModel\Campaign;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'campaign_id';

    protected function _construct()
    {
        $this->_init(
            'Dtn\Campaign\Model\Campaign',
            'Dtn\Campaign\Model\ResourceModel\Campaign'
        );
    }
}
