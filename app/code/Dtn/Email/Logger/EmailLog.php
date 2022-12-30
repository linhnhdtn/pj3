<?php

namespace Dtn\Email\Logger;

use Monolog\Logger;

class EmailLog extends \Magento\Framework\Logger\Handler\Base
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
    protected $fileName = '/var/log/email/send_email_notify.log';
}
