<?php

namespace Dtn\Campaign\Cron;

use Dtn\Campaign\Model\ResourceModel\Campaign\Collection as CampaignCollection;
use Dtn\Campaign\Helper\Campaign as CampaignHelper;
use Dtn\Campaign\Logger\Logger;
class Process
{
    /**
     * @var CampaignHelper
     */
    protected $helperCampaign;
    /**
     * @var CampaignCollection
     */
    protected $campaignCollection;
    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @param CampaignCollection $campaignCollection
     * @param CampaignHelper $helperCampaign
     * @param Logger $logger
     */
    public function __construct
    (
        CampaignCollection $campaignCollection,
        CampaignHelper     $helperCampaign,
        Logger             $logger
    )
    {
        $this->logger = $logger;
        $this->helperCampaign = $helperCampaign;
        $this->campaignCollection = $campaignCollection;
    }

    public function execute()
    {
//         PENDING = 0;
//         SUCCESS = 1;
//         SCHEDULED = 2;
//         PROCESSING = 3;
//         ERROR = 4;
        $time = date("Y-m-d H:i:s", (time()));
        $campaigns = $this->campaignCollection->load();

        foreach ($campaigns as $campaign) {
            $status = $campaign->getStatus();
            if ($status == 2) {
                if ($campaign->getStartDate() == $time) {
                    try {
                        // set status campaign
                        $campaign->setStatus(3);
                        $campaign->setEnable(true);
                        $campaign->save();

                        // enable product , page , block in campaign
                        $this->helperCampaign->updateStatusProduct($campaign->getId(), true);
                        $this->helperCampaign->updateStatusBlock($campaign->getId(), true);
                        $this->helperCampaign->updateStatusPage($campaign->getId(), true);

                    } catch (\Exception $exception) {
                        $campaign->setStatus(4);
                        $campaign->save();
                        $this->logger->info($exception->getMessage());
                    }
                }
            } elseif ($status == 3) {
                if ($campaign->getEndDate() == $time) {
                    try {
                        // set status campaign
                        $campaign->setStatus(1);
                        $campaign->setEnable(false);
                        $campaign->save();

                        // disable product , page , block in campaign
                        $this->helperCampaign->updateStatusProduct($campaign->getId(), false);
                        $this->helperCampaign->updateStatusBlock($campaign->getId(), false);
                        $this->helperCampaign->updateStatusPage($campaign->getId(), false);

                    } catch (\Exception $exception) {
                        $campaign->setStatus(4);
                        $campaign->save();
                        $this->logger->info($exception->getMessage());
                    }
                }
            }
        }
    }
}
