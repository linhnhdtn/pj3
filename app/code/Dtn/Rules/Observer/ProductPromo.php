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


class ProductPromo implements ObserverInterface
{
    const DATA = 'test/general_setting/fieldname';

    protected $session;

    protected $productFactory;

    protected $productRepository;

    protected $_scopeConfig;

    protected $messageManager;

    protected $quote;

    protected $cart;

    protected $request;

    public function __construct(
        Session              $session,
        ProductFactory       $productFactory,
        ProductRepository    $productRepository,
        ScopeConfigInterface $_scopeConfig,
        ManagerInterface     $messageManager,
        Quote                $quote,
        Cart         $cart,
        \Magento\Framework\App\RequestInterface $request

    )
    {
        $this->session = $session;
        $this->productFactory = $productFactory;
        $this->productRepository = $productRepository;
        $this->_scopeConfig = $_scopeConfig;
        $this->messageManager = $messageManager;
        $this->quote = $quote;
        $this->cart = $cart;
        $this->request = $request;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        // get request data product add cart
        $request =  $this->request->getParams();
        if ($request){
            $id = $request["product"];
        }
        $productAddCart = $this->productRepository->getById($id);

        // get data system config
        $dataConfig = json_decode($this->_scopeConfig->getValue(self::DATA), true);

        // get data cart
        $cartItems = $this->session->getQuote()->getAllItems();
        $totalCart = $this->session->getQuote()->getGrandTotal();

        if ($dataConfig) {
            foreach ($dataConfig as $data) {
                $priceLimit = $data["price_limit"];
                $sku_product = $data["sku_product"];
                $store = $data["store"];

                if ($productAddCart->getSku() == $sku_product) {
                    if ($totalCart >= $priceLimit) {
                      continue;
                    } else {
                        foreach ($cartItems as $item){
                            $itemId = $item->getItemId();
                            $this->cart->removeItem($itemId)->save();
                        }
                        $this->messageManager->addError(__('total price cart >'). $priceLimit );
                    }
                }
            }
        }


    }

}
