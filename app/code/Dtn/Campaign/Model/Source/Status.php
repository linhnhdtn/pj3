<?php

namespace Dtn\Campaign\Model\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Eav\Model\Entity\Attribute\Source\SourceInterface;
use Magento\Framework\Data\OptionSourceInterface;

/**
 * Item status functionality model
 */
class Status extends AbstractSource implements SourceInterface, OptionSourceInterface
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
            self::PENDING => __('<span class="data-grid-severity-pending"><span>' . "Pending" . '</span></span>'),
            self::SUCCESS => __('<span class="grid-severity-notice"><span>' . "Success" . '</span></span>'),
            self::SCHEDULED => __('<span class="grid-severity-minor"><span>' . "Schedule" . '</span></span>'),
            self::PROCESSING => __('<span class="data-grid-severity-processing"><span>' . "Processing" . '</span></span>'),
            self::ERROR => __('<span class="data-grid-severity-critical"><span>' . "Error" . '</span></span>')
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
        $class = '';
        $text = '';
        foreach (self::getOptionArray() as $index => $value) {
            switch ($index) {
                case 0:
                    $class = 'grid-severity-pending';
                    $text = __('Pending');
                    $result[] = ['value' => $index, 'label' => $value];
                    break;
                case 1:
                    $class = 'grid-severity-notice';
                    $text = __('Success');
                    $result[] = ['value' => $index, 'label' => $value];
                    break;
                case 3:
                    $class = 'grid-severity-processing';
                    $text = __('Processing');
                    $result[] = ['value' => $index, 'label' => $value];
                    break;
                case 2:
                    $class = 'grid-severity-minor';
                    $text = __('Schedule');
                    $result[] = ['value' => $index, 'label' => $value];
                    break;
                case 4:
                    $class = 'grid-severity-critical';
                    $text = __('Error');
                    $result[] = ['value' => $index, 'label' => $value];
                    break;
            }
        }

        return $result;
    }
}
