<?php

namespace Dtn\Email\Ui\Component\Listing\Column\Status\Options;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Sales\Model\ResourceModel\Order\Status\CollectionFactory;

class StatusOrder implements OptionSourceInterface
{
    /**
     * @var array
     */
    protected $options;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * Constructor
     *
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(CollectionFactory $collectionFactory)
    {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        if ($this->options === null) {
            $options = $this->collectionFactory->create()->toOptionArray();
            $this->options = $options;
        }
        return $this->options;
    }
}
