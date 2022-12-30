<?php

namespace Dtn\Email\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;

class EmailActions extends Column
{
    const URL_PATH_EDIT = 'dtn_email/email/edit';

    /**
     * URL builder
     *
     * @var \Magento\Framework\UrlInterface
     */
    protected $_urlBuilder;

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

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $item[$this->getData('name')] = [
                    'edit' => [
                        'href' => $this->_urlBuilder->getUrl(
                            static::URL_PATH_EDIT,
                            [
                                'id' => $item['id']
                            ]
                        ),
                        'label' => __('Edit')
                    ]
                ];
            }
        }
        return $dataSource;
    }
}
