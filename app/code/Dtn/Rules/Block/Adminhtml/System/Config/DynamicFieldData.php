<?php

namespace Dtn\Rules\Block\Adminhtml\System\Config;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\DataObject;
use Magento\Framework\Registry;

class DynamicFieldData extends AbstractFieldArray
{

    public function __construct(
        Context $context,
        Registry $coreRegistry,
        array $data = []
    ) {
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context, $data);
    }

    protected function _prepareToRender()
    {
        $this->addColumn(
            'store',
            [
                'label' => __('Store'),
                'class' => 'required-entry',
                'style' => 'width:100px',
            ]
        );

        $this->addColumn(
            'sku_product',
            [
                'label' => __('Sku'),
                'class' => 'required-entry',
                'style' => 'width:100px',
            ]
        );

        $this->addColumn(
            'price_limit',
            [
                'label' => __('Price Limit'),
                'class' => 'required-entry',
                'style' => 'width:100px',
            ]
        );


        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
    }

    protected function _prepareArrayRow(DataObject $row): void
    {
        $options = [];
        $selectFieldData = $row->getSelectField();
        if ($selectFieldData !== null) {
            $options['option_' . $this->getSelectFieldOptions()->calcOptionHash($selectFieldData)] = 'selected="selected"';
        }
        $row->setData('option_extra_attrs', $options);
    }

    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $html = parent::_getElementHtml($element);

        $script = '<script type="text/javascript">
                require(["jquery", "jquery/ui", "mage/calendar"], function (jq) {
                    jq(function(){
                        function bindDatePicker() {
                            setTimeout(function() {
                                jq(".daterecuring").datepicker( { dateFormat: "mm/dd/yy" } );
                            }, 50);
                        }
                        bindDatePicker();
                        jq("button.action-add").on("click", function(e) {
                            bindDatePicker();
                        });
                    });
                });
            </script>';
        $html .= $script;
        return $html;
    }
}
