<?php

namespace Dtn\Campaign\Controller\Adminhtml\Items;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Dtn\Campaign\Model\CampaignFactory;
use Magento\Backend\App\Action;

class InlineEdit extends Action
{
    /**
     * @var CampaignFactory
     */
    protected $campaignFactory;
    /**
     * @var JsonFactory
     */
    protected $jsonFactory;

    /**
     * @param Context $context
     * @param JsonFactory $jsonFactory
     * @param CampaignFactory $campaignFactory
     */
    public function __construct(
        Context         $context,
        JsonFactory     $jsonFactory,
        CampaignFactory $campaignFactory
    )
    {
        $this->campaignFactory = $campaignFactory;
        $this->jsonFactory = $jsonFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        if ($this->getRequest()->getParam('isAjax')) {
            $postItems = $this->getRequest()->getParam('items', []);
            if (!count($postItems)) {
                $messages[] = __('Please correct the data sent.');
                $error = true;
            } else {
                foreach (array_keys($postItems) as $modelid) {
                    $model = $this->campaignFactory->create()->load($modelid);
                    try {
                        $model->setData(array_merge($model->getData(), $postItems[$modelid]));
                        $model->save();
                    } catch (\Exception $e) {
                        $messages[] = "[Mytesting ID: {$modelid}]  {$e->getMessage()}";
                        $error = true;
                    }
                }
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }
}
