<?php

namespace Dtn\Email\Controller\Adminhtml\Email;

use Dtn\Email\Controller\Adminhtml\Email as Emails;

class NewAction extends Emails
{
    public function execute()
    {
        $this->_forward('edit');
    }
}
