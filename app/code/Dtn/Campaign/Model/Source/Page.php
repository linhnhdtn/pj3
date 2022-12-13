<?php

namespace Dtn\Campaign\Model\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Eav\Model\Entity\Attribute\Source\SourceInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Cms\Api\PageRepositoryInterface;
use Magento\Framework\Data\OptionSourceInterface;

class Page extends AbstractSource implements SourceInterface, OptionSourceInterface
{
    /**
     * @var PageRepositoryInterface
     */
    protected $pageRepositoryInterface;
    /**
     * @var SearchCriteriaBuilder
     */
    protected $_searchCriteriaBuilder;

    /**
     * @param PageRepositoryInterface $pageRepositoryInterface
     * @param SearchCriteriaBuilder $_searchCriteriaBuilder
     */
    public function __construct
    (
        PageRepositoryInterface $pageRepositoryInterface,
        SearchCriteriaBuilder   $_searchCriteriaBuilder
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
        $pages = $this->pageRepositoryInterface->getList($searchCriteria)->getItems();
        foreach ($pages as $page) {
            $pageArray[] = [
                'value' => $page->getId(),
                'label' => $page->getTitle()
            ];
        }
        return $pageArray;
    }
}
