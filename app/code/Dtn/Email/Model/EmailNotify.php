<?php

namespace Dtn\Email\Model;

use Magento\Framework\Model\AbstractModel;

class EmailNotify extends AbstractModel
{
    protected function _construct()
    {
        $this->_init('Dtn\Email\Model\ResourceModel\EmailNotify');
    }
}
