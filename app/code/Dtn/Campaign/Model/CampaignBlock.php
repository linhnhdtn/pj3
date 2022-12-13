<?php

namespace Dtn\Campaign\Model;

use Magento\Framework\Model\AbstractModel;

class CampaignBlock extends AbstractModel
{
    protected function _construct()
    {
        $this->_init('Dtn\Campaign\Model\ResourceModel\CampaignBlock');
    }
}
