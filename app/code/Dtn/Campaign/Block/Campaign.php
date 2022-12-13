<?php

namespace Dtn\Campaign\Block;

class Campaign extends \Magento\Framework\View\Element\Template
{
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context
    ) {
        parent::__construct($context);
    }

    public function _prepareLayout()
    {
        $this->pageConfig->getTitle()->set(__('Dtn Campaign Module'));

        return parent::_prepareLayout();
    }
}
