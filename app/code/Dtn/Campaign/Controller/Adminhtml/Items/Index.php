<?php

namespace Dtn\Campaign\Controller\Adminhtml\Items;

use Dtn\Campaign\Controller\Adminhtml\Items;

class Index extends Items
{
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('Campaign'));
        return $resultPage;
    }
}
