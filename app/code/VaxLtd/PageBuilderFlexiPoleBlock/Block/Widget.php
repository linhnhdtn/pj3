<?php

declare(strict_types=1);

namespace VaxLtd\PageBuilderFlexiPoleBlock\Block;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Widget\Block\BlockInterface;

class Widget extends \Magento\Framework\View\Element\Template implements BlockInterface
{
    /**
     * @var Json
     */
    private $serializer;

    public function __construct(
        Json $serializer,
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->serializer = $serializer;
    }

    /**
     * get items array
     *
     * @return array
     */
    public function getItems()
    {
        $itemsJson = $this->getData('items');
        $items = [];

        if ($itemsJson != "") {
            try {
                $itemsJson = str_replace(['`', '|', '&lt;', '&gt;'], ['"', '\\', '<', '>'], $itemsJson);
                $items = $this->serializer->unserialize($itemsJson);
            } catch (\InvalidArgumentException $ex) {
                $items = [];
            }
        }

        return $items;
    }
}
