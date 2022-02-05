<?php

namespace Dtn\Rules\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Checkout\Model\Session;
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Model\ProductRepository;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Quote\Model\Quote;
use Magento\Checkout\Model\Cart;


class DeleteItem implements ObserverInterface
{
    const DATA = 'test/general_setting/fieldname';

    protected $session;

    protected $productFactory;

    protected $productRepository;

    protected $_scopeConfig;

    protected $messageManager;

    protected $quote;

    protected $cart;

    public function __construct(
        Session              $session,
        ProductFactory       $productFactory,
        ProductRepository    $productRepository,
        ScopeConfigInterface $_scopeConfig,
        ManagerInterface     $messageManager,
        Quote                $quote,
        Cart                 $cart

    )
    {
        $this->session = $session;
        $this->productFactory = $productFactory;
        $this->productRepository = $productRepository;
        $this->_scopeConfig = $_scopeConfig;
        $this->messageManager = $messageManager;
        $this->quote = $quote;
        $this->cart = $cart;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $info = $observer->getInfo();
        $product = $observer->getProduct();
        $cartItems = $this->session->getQuote()->getAllItems();
        $dataConfig = json_decode($this->_scopeConfig->getValue(self::DATA), true);

        if ($dataConfig) {
            foreach ($dataConfig as $data) {
                $sku_product = $data["sku_product"];
            }

            foreach ($cartItems as $item ){
                $idSkuItem = $this->productRepository->get($sku_product)->getSku();
                if (($idSkuItem == $item->getSku()) && ($idSkuItem == $sku_product)) {
                    if ($item->getQty() > 1){
                        $cartItems[$item->getId()]['qty'] = 1;
                        $cartItems = $this->cart->suggestItemsQty($cartItems);
                        $this->cart->updateItems($cartItems)->save();
                        $this->messageManager->addError(__('Only 1 product Allowed to Purchase at a time.'));
                    } else {
                        continue;
                    }
                }
            }
        }
    }
}
