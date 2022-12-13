<?php

namespace Dtn\Campaign\Model\Source;

use Magento\Framework\Option\ArrayInterface;

abstract class OptionArray implements ArrayInterface
{
    public function toOptionArray()
    {
        $options = [];
        $hash = $this->getOptionHash();
        foreach ($hash as $value => $label) {
            $options[] = [
                'value' => $value,
                'label' => $label
            ];
        }

        return $options;
    }

    abstract public function getOptionHash();
}