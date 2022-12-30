<?php

namespace Dtn\Email\Model\ResourceModel;

class EmailNotify extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init('email_notify', 'id');
    }
}
