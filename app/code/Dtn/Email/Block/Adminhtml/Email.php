<?php

namespace Dtn\Email\Block\Adminhtml;

use Magento\Framework\View\Element\Template;

class Email extends \Magento\Sales\Block\Items\AbstractItems
{
    public function getItems(){
        return $this->getData('skus');
    }
}
