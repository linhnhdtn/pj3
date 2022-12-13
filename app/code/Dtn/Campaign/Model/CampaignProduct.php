<?php

namespace Dtn\Campaign\Model;

use Magento\Framework\Model\AbstractModel;

class CampaignProduct extends AbstractModel
{
    protected function _construct()
    {
        $this->_init('Dtn\Campaign\Model\ResourceModel\CampaignProduct');
    }
}
