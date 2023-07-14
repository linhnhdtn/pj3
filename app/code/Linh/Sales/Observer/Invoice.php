<?php

namespace Linh\Sales\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Model\Service\InvoiceService;
use Magento\Framework\DB\Transaction;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Invoice implements ObserverInterface
{
    const AUTO_INVOICE = 'order/invoice/enable';
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;
    /**
     * @var InvoiceService
     */
    protected $invoiceService;
    /**
     * @var Transaction
     */
    protected $transaction;

    public function __construct
    (
        ScopeConfigInterface $scopeConfig,
        InvoiceService       $invoiceService,
        Transaction          $transaction
    )
    {
        $this->invoiceService = $invoiceService;
        $this->scopeConfig = $scopeConfig;
        $this->transaction = $transaction;
    }


    public function execute(Observer $observer)
    {
//        $order = $observer->getData("order");
//        try {
//            if ($this->scopeConfig->isSetFlag(self::AUTO_INVOICE)) {
//                if ($order->canInvoice()) {
//                    $invoice = $this->invoiceService->prepareInvoice($order);
//                    $invoice->register();
//                    $invoice->save();
//                }
//            }
//        } catch (\Exception $e){
//           echo $e->getMessage();
//        }
    }
}
