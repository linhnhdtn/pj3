<?php

namespace Dtn\Email\Controller\Adminhtml\Email;

use Dtn\Email\Controller\Adminhtml\Email;

class Index extends Email
{
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('Email Notify'));
        return $resultPage;
    }
}
