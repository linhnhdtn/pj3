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

class Delete extends Emails
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
            try {
                $emailNotifyFactory->load($id);
                $emailNotifyFactory->delete();
                $this->messageManager->addSuccess(__('You deleted the item.'));
                $this->_redirect('dtn_campaign/*/');
            } catch (\Exception $exception) {
                $this->messageManager->addError(
                    __('We can\'t delete item right now. Please review the log and try again.')
                );
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($exception);
                $this->_redirect('dtn_email/*/edit', ['id' => $this->getRequest()->getParam('id')]);
                return;
            }
        }
        $this->messageManager->addError(__('We can\'t find a item to delete.'));
        $this->_redirect('dtn_campaign/*/');
    }
}
