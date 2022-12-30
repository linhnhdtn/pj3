<?php

namespace Dtn\Email\Controller\Adminhtml\Email;

use Dtn\Email\Controller\Adminhtml\Email as Emails;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\Image\AdapterFactory;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\Model\Session;
use Dtn\Email\Model\EmailNotifyFactory;

class Edit extends Emails
{

    /**
     * @var Session
     */
    protected $session;

    /**
     * @var EmailNotifyFactory
     */
    protected $emailNotifyFactory;

    /**
     * @var Registry
     */
    protected $_registry;

    public function __construct(
        Context                $context,
        Registry               $coreRegistry,
        ForwardFactory         $resultForwardFactory,
        PageFactory            $resultPageFactory,
        DirectoryList          $directoryList,
        UploaderFactory        $uploaderFactory,
        AdapterFactory         $adapterFactory,
        Filesystem             $filesystem,
        Filesystem\Driver\File $file,
        Session                $session,
        EmailNotifyFactory     $emailNotifyFactory,
        Registry               $_registry
    )
    {
        $this->_registry = $_registry;
        $this->emailNotifyFactory = $emailNotifyFactory;
        $this->session = $session;
        parent::__construct(
            $context,
            $coreRegistry,
            $resultForwardFactory,
            $resultPageFactory,
            $directoryList,
            $uploaderFactory,
            $adapterFactory,
            $filesystem,
            $file
        );
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $emailNotifyFactory = $this->emailNotifyFactory->create();
        if ($id) {
            $emailNotifyFactory->load($id);
            if (!$emailNotifyFactory->getId()) {
                $this->messageManager->addError(__('This item no longer exists.'));
                $this->_redirect('dtn_campaign/*');
                return;
            }
        }

        $this->_registry->register('email_notify' , $emailNotifyFactory);
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->setKeywords(__('Edit Page'));
        $resultPage->getConfig()->getTitle()->prepend("Notification of missing products");
        return $resultPage;

    }
}
