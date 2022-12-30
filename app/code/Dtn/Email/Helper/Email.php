<?php

namespace Dtn\Email\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Dtn\Email\Model\ResourceModel\Product\CollectionFactory as ProductCollection;
use Dtn\Email\Model\ResourceModel\EmailNotify\CollectionFactory as EmailNotifyCollection;
use Dtn\Email\Model\ProductFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Escaper;
use Dtn\Email\Logger\Logger;
use Dtn\Email\Model\HistoryFactory;
use Dtn\Email\Model\EmailNotifyFactory;

class Email extends AbstractHelper
{
    const SENDER_EMAIL = 'trans_email/ident_general/email';
    const SENDER_NAME = 'trans_email/ident_general/name';


    /**
     * @var EmailNotifyFactory
     */
    protected $emailNotifyFactory;
    /**
     * @var HistoryFactory
     */
    protected $historyFactory;
    /**
     * @var Logger
     */
    protected $logger;
    /**
     * @var EmailNotifyCollection
     */
    protected $emailNoitifyCollection;

    /**
     * @var ProductFactory
     */
    protected $productFactory;

    /**
     * @var ProductCollection
     */
    protected $productCollection;

    /**
     * @var Escaper
     */
    protected $escaper;

    /**
     * @var TransportBuilder
     */
    protected $transportBuilder;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    public function __construct(
        Context               $context,
        ProductCollection     $productCollection,
        ProductFactory        $productFactory,
        EmailNotifyCollection $emailNotifyCollection,
        Escaper               $escaper,
        StoreManagerInterface $storeManager,
        ScopeConfigInterface  $scopeConfig,
        TransportBuilder      $transportBuilder,
        Logger                $logger,
        HistoryFactory        $historyFactory,
        EmailNotifyFactory    $emailNotifyFactory
    )
    {
        $this->emailNotifyFactory = $emailNotifyFactory;
        $this->historyFactory = $historyFactory;
        $this->logger = $logger;
        $this->escaper = $escaper;
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->transportBuilder = $transportBuilder;
        $this->emailNoitifyCollection = $emailNotifyCollection;
        $this->productFactory = $productFactory;
        $this->productCollection = $productCollection;
        parent::__construct($context);
    }

    public function sendEmail($incrementId, $skus, $customerEmail, $jobId, $storeId)
    {
        try {
            $senderName = $this->scopeConfig->getValue(self::SENDER_NAME, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
            $senderEmail = $this->scopeConfig->getValue(self::SENDER_EMAIL, \Magento\Store\Model\ScopeInterface::SCOPE_STORE , $storeId);
            $sender = [
                'name' => $this->escaper->escapeHtml($senderName),
                'email' => $this->escaper->escapeHtml($senderEmail),
            ];

            $varsTemplate = [
                'increment_id' => $incrementId,
                'skus' => $skus
            ];
            $transport = $this->transportBuilder
                ->setTemplateIdentifier('send_email_order_product_miss')
                ->setTemplateOptions(
                    [
                        'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                        'store' => $storeId,
                    ]
                )
                ->setTemplateVars($varsTemplate)
                ->setFromByScope($sender)
                ->addTo($customerEmail)
                ->getTransport();

            $transport->sendMessage();

            // disable job
            $jobSendEmail = $this->emailNotifyFactory->create()->load($jobId);
            $jobSendEmail->setEnable(false);
            $jobSendEmail->save();

            // save history send email
            $history = $this->historyFactory->create();
            $history->setEmail($customerEmail);
            $history->setIncrementId($incrementId);
            $history->setEmailNotifyId($jobId);
            $history->save();
            $this->logger->info("send mail notification done order increment id = " . $incrementId);

        } catch (\Exception $exception) {
            $this->logger->err($exception->getMessage());
        }
    }

    public function getProduct($skus){
        return $skus;
    }

    public function getCollectionJobEmailNotify()
    {
        $collection = $this->emailNoitifyCollection->create()
            ->addFieldToFilter("enable", ["eq" => 1])
            ->load();
        return $collection;
    }

    public function getCollectionProductMiss($jobId)
    {
        $collectionProduct = $this->productCollection->create()
            ->addFieldToFilter("email_notify_id", ["eq" => $jobId])
            ->load();
        return $collectionProduct;
    }

    public function deleteProductMiss($jobId)
    {
        $products = $this->getCollectionProductMiss($jobId);
        foreach ($products as $product) {
            $this->productFactory->create()->load($product->getId())->delete();
        }
    }
}
