<?php

namespace Dtn\Email\Model\ResourceModel;

class History extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init('history_send_email', 'id');
    }
}
