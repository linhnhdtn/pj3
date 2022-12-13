<?php

namespace Dtn\Campaign\Model\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Eav\Model\Entity\Attribute\Source\SourceInterface;
use Magento\Framework\Data\OptionSourceInterface;

class StatusEdit extends AbstractSource implements SourceInterface, OptionSourceInterface
{
    const PENDING = 0;
    const SUCCESS = 1;
    const SCHEDULED = 2;
    const PROCESSING = 3;
    const ERROR = 4;

    /**#@-*/

    /**
     * Retrieve option array
     *
     * @return string[]
     */
    public static function getOptionArray()
    {
        return [
            self::PENDING => __('Pending'),
            self::SUCCESS => __('Success'),
            self::SCHEDULED => __('Schedule'),
            self::PROCESSING => __('Processing'),
            self::ERROR => __('Error')
        ];
    }

    /**
     * Retrieve option array with empty value
     *
     * @return string[]
     */
    public function getAllOptions()
    {
        $result = [];
        foreach (self::getOptionArray() as $index => $value) {
            $result[] = ['value' => $index, 'label' => $value];
        }
        return $result;
    }
}
