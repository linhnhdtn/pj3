<?php

namespace Dtn\Campaign\Controller\Adminhtml\Items;

use Dtn\Campaign\Controller\Adminhtml\Items;

class NewAction extends Items
{
    public function execute()
    {
        $this->_forward('edit');
    }
}
