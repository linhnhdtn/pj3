<?php

namespace Dtn\Campaign\Model;

use Magento\Framework\Model\AbstractModel;

class Campaign extends AbstractModel
{
    protected function _construct()
    {
        $this->_init('Dtn\Campaign\Model\ResourceModel\Campaign');
    }
}
