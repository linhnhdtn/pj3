<?php

namespace Linh\Sales\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;

class ShipCode extends Column
{
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $item[$this->getData('name')] = $item["ship_code"];
            }
        }
        return $dataSource;
    }

}
