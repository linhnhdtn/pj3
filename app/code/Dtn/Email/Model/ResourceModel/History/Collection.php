<?php

namespace Dtn\Email\Model\ResourceModel\History;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';

    protected function _construct()
    {
        $this->_init(
            'Dtn\Email\Model\History',
            'Dtn\Email\Model\ResourceModel\History'
        );
    }
}
