<?php

namespace Dtn\Campaign\Logger;

use Monolog\Logger;

class CampaignLog extends \Magento\Framework\Logger\Handler\Base
{
    /**
     * Logging level
     * @var int
     */
    protected $loggerType = Logger::INFO;

    /**
     * File name
     * @var string
     */
    protected $fileName = '/var/log/campaign/cron_campaign.log';
}
