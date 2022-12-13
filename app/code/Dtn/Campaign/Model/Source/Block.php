<?php

namespace Dtn\Campaign\Model\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Eav\Model\Entity\Attribute\Source\SourceInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Cms\Api\BlockRepositoryInterface;
use Magento\Framework\Data\OptionSourceInterface;

class Block extends AbstractSource implements SourceInterface, OptionSourceInterface
{
    /**
     * @var BlockRepositoryInterface
     */
    protected $pageRepositoryInterface;
    /**
     * @var SearchCriteriaBuilder
     */
    protected $_searchCriteriaBuilder;

    /**
     * @param BlockRepositoryInterface $pageRepositoryInterface
     * @param SearchCriteriaBuilder $_searchCriteriaBuilder
     */
    public function __construct
    (
        BlockRepositoryInterface $pageRepositoryInterface,
        SearchCriteriaBuilder    $_searchCriteriaBuilder
    )
    {
        $this->pageRepositoryInterface = $pageRepositoryInterface;
        $this->_searchCriteriaBuilder = $_searchCriteriaBuilder;
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getAllOptions()
    {
        $searchCriteria = $this->_searchCriteriaBuilder->create();
        $blocks = $this->pageRepositoryInterface->getList($searchCriteria)->getItems();
        foreach ($blocks as $block) {
            $blocksArray[] = [
                'value' => $block->getId(),
                'label' => $block->getTitle()
            ];
        }
        return $blocksArray;
    }
}
