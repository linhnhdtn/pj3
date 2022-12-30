<?php

namespace Dtn\Email\Controller\Adminhtml;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\Image\AdapterFactory;
use Magento\Framework\Filesystem;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Filesystem\Driver\File;

abstract class Email extends Action
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @var \Magento\Backend\Model\View\Result\ForwardFactory
     */
    protected $resultForwardFactory;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var UploaderFactory
     */
    protected $uploaderFactory;

    /**
     * @var AdapterFactory
     */
    protected $adapterFactory;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    public function __construct(
        Context         $context,
        Registry        $coreRegistry,
        ForwardFactory  $resultForwardFactory,
        PageFactory     $resultPageFactory,
        DirectoryList   $directoryList,
        UploaderFactory $uploaderFactory,
        AdapterFactory  $adapterFactory,
        Filesystem      $filesystem,
        File            $file
    )
    {
        $this->_coreRegistry = $coreRegistry;
        $this->resultForwardFactory = $resultForwardFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->directoryList = $directoryList;
        $this->uploaderFactory = $uploaderFactory;
        $this->adapterFactory = $adapterFactory;
        $this->filesystem = $filesystem;
        $this->_file = $file;
        parent::__construct($context);
    }

    protected function _initAction()
    {
        $this->_view->loadLayout();
        $this->_setActiveMenu('Dtn_Email::email')->_addBreadcrumb(__('Email'), __('Email'));
        return $this;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Dtn_Email::email');
    }
}
