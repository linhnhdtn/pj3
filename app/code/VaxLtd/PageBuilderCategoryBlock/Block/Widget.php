<?php

declare(strict_types=1);

namespace VaxLtd\PageBuilderCategoryBlock\Block;

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

    /**
     * @param $fileJson
     * @return array|bool|float|int|mixed|string|null
     */
    public function convertFile($fileJson)
    {
        $file = [];
        if ($fileJson != "") {
            try {
                $fileJson = str_replace(['`', '|', '<', '>'], ['"', '\\', '<', '>'], $fileJson);
                $file = $this->serializer->unserialize($fileJson);
            } catch (\InvalidArgumentException $exception) {
                $file = [];
            }
        }

        return $file;
    }
}
