<?php

namespace Dtn\Campaign\Block\Adminhtml\Items;

class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    protected function _construct()
    {
        $this->_objectId = 'id';
        $this->_controller = 'adminhtml_items';
        $this->_blockGroup = 'Dtn_Campaign';

        parent::_construct();

//        $this->buttonList->add(
//            'save_and_continue',
//            [
//                'class' => 'save',
//                'label' => __('Save and Continue Edit'),
//                'data_attribute' => [
//                    'mage-init' => ['button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form']],
//                ]
//            ],
//            10
//        );
    }

}
