<?php

namespace Dtn\Campaign\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;

class CampaignActions extends Column
{
    const URL_PATH_EDIT = 'dtn_campaign/items/edit';
    const URL_PATH_SCHEDULE = 'dtn_campaign/items/schedule';

    /**
     * URL builder
     *
     * @var \Magento\Framework\UrlInterface
     */
    protected $_urlBuilder;

    /**
     * @param UrlInterface $urlBuilder
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param array $components
     * @param array $data
     */
    public function __construct(
        UrlInterface       $urlBuilder,
        ContextInterface   $context,
        UiComponentFactory $uiComponentFactory,
        array              $components = [],
        array              $data = []
    )
    {
        $this->_urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $item[$this->getData('name')] = [
                    'edit' => [
                        'href' => $this->_urlBuilder->getUrl(
                            static::URL_PATH_EDIT,
                            [
                                'campaign_id' => $item['campaign_id']
                            ]
                        ),
                        'label' => __('Edit')
                    ],
                    'schedule' => [
                        'href' => $this->_urlBuilder->getUrl(
                            static::URL_PATH_SCHEDULE,
                            [
                                'id' => $item['campaign_id']
                            ]
                        ),
                        'label' => __('Schedule Job')
                    ],
                ];
            }
        }
        return $dataSource;
    }
}
