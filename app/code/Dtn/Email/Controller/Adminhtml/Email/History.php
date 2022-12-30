<?php

namespace Dtn\Email\Controller\Adminhtml\Email;

use Dtn\Email\Controller\Adminhtml\Email;

class History extends Email
{
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('History Send Email'));
        return $resultPage;
    }
}
