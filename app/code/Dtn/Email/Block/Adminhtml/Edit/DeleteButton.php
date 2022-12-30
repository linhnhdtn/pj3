<?php

namespace Dtn\Email\Block\Adminhtml\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class DeleteButton extends GenericButton implements ButtonProviderInterface
{

    public function getButtonData()
    {
        return [
            'label' => __('Delete'),
            'on_click' => sprintf("location.href = '%s';", $this->getBackUrl()),
            'class' => 'delete',
            'sort_order' => 10
        ];
    }

    public function getBackUrl()
    {
        return $this->getUrl('dtn_email/email/delete');
    }
}
