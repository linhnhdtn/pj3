<?php

namespace Dtn\Email\Model;

use Magento\Framework\Model\AbstractModel;

class Product extends AbstractModel
{
    protected function _construct()
    {
        $this->_init('Dtn\Email\Model\ResourceModel\Product');
    }
}
