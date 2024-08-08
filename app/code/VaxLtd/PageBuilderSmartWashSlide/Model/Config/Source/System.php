<?php

namespace VaxLtd\PageBuilderSmartWashSlide\Model\Config\Source;

class System implements \Magento\Framework\Data\OptionSourceInterface
{

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {

        $options = [
            [
                'label' => 'Left',
                'value' => 'left',
            ],
            [
                'label' => 'Right',
                'value' => '_right',
            ],
            [
                'label' => 'Center',
                'value' => 'center',
            ]
        ];
        return $options;
    }
}
