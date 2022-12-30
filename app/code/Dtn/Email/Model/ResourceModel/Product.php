<?php

namespace Dtn\Email\Model\ResourceModel;

class Product extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init('product_miss', 'id');
    }
}
