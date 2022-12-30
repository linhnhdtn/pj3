<?php

namespace Dtn\Email\Controller\Adminhtml\Email;

use Dtn\Email\Controller\Adminhtml\Email as Emails;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\Image\AdapterFactory;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Dtn\Email\Model\EmailNotifyFactory;
use Dtn\Email\Model\ProductFactory as ProductMiss;
use Dtn\Email\Helper\Email as HelperEmail;

class Save extends Emails
{
    /**
     * @var ProductMiss
     */
    protected $productMiss;
    /**
     * @var EmailNotifyFactory
     */
    protected $emailNotifyFactory;
    /**
     * @var HelperEmail
     */
    protected $helperEmail;

    public function __construct(
        Context            $context,
        Registry           $coreRegistry,
        ForwardFactory     $resultForwardFactory,
        PageFactory        $resultPageFactory,
        DirectoryList      $directoryList,
        UploaderFactory    $uploaderFactory,
        AdapterFactory     $adapterFactory,
        Filesystem         $filesystem,
        File               $file,
        EmailNotifyFactory $emailNotifyFactory,
        ProductMiss        $productMiss,
        HelperEmail        $helperEmail
    )
    {
        $this->helperEmail = $helperEmail;
        $this->productMiss = $productMiss;
        $this->emailNotifyFactory = $emailNotifyFactory;
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
        if ($this->getRequest()->getPostValue()) {
            try {
                $emailNotifyFactory = $this->emailNotifyFactory->create();
                $data = $this->getRequest()->getPostValue();
                $id = $this->getRequest()->getParam('id');
                if (!$id) {
                    $emailNotifyFactory->load($id);
                    if ($id != $emailNotifyFactory->getId()) {
                        throw new \Magento\Framework\Exception\LocalizedException(__('The wrong item is specified.'));
                    }
                }
                $status = implode(",", $data["email"]["status"]);
                $emailNotifyFactory->setData($data["email"]);
                $emailNotifyFactory->setStatus($status);
                $this->_session->setPageData($emailNotifyFactory->getData("email"));
                $job = $emailNotifyFactory->save();

                $jobId = $job->getId();
                // save product miss
                if (!empty($data["product"]["data"]["links"]["product"])) {
                    $this->helperEmail->deleteProductMiss($jobId);
                    foreach ($data["product"]["data"]["links"]["product"] as $product) {
                        $productFactory = $this->productMiss->create();
                        $productFactory->setProductId($product["id"]);
                        $productFactory->setProductSku($product["sku"]);
                        $productFactory->setProductName($product["name"]);
                        $productFactory->setEmailNotifyId($jobId);
                        $productFactory->save();
                    }
                } else {
                    $this->helperEmail->deleteProductMiss($jobId);
                }

                $this->messageManager->addSuccess(__('You saved the job send email'));
                $this->_session->setPageData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('dtn_email/*/edit', ['id' => $emailNotifyFactory->getId()]);
                    return;
                }
                $this->_redirect('dtn_email/*/');

            } catch (\Exception $exception) {
                $this->messageManager->addError($exception->getMessage());
                if (!empty($id)) {
                    $this->_redirect('dtn_email/*/edit', ['id' => $id]);
                } else {
                    $this->_redirect('dtn_email/*/new');
                }
                return;
            }
        }
    }
}
