<?php

namespace Integra\Infrastructure\Queue\Job\Triggers;

use Integra\Domain\Services\MindBoxService;
use Integra\Infrastructure\Queue\BaseJob;
use Integra\Infrastructure\Queue\Job\Integrations\MindBox\MindBoxBetChangeJob;
use Integra\Models\Ubet\SportBet;
use yii\helpers\StringHelper;
use yii\queue\Queue;
use Yii;

class JobTriggerChangeBetShare extends BaseJob
{
    public int $orderNumber;
    private MindBoxService $mindBoxService;

    public function init(): void
    {
        parent::init();
        $this->mindBoxService = new MindBoxService();
    }

    /**
     * @inheritDoc
     */
    protected function handle(Queue $queue): void
    {
        $this->mindBoxTrigger();
    }

    private function mindboxTrigger():void
    {
        $bet = SportBet::findOne(['order_number' => $this->orderNumber, 'tr_name' => 'CreditBet']);

        if (!$bet) {
            $message = sprintf(
                '[%s] Bet not found:: %s',
                StringHelper::basename(self::class),
                $this->orderNumber
            );
            Yii::error($message, __METHOD__);

            return;
        }

        if ($this->mindBoxService->reactivateUserIfNotExistMindbox($bet->user_id, true)) {
            $mindboxChangeBetJob = new MindBoxBetChangeJob();
            $mindboxChangeBetJob->orderNumber = $this->orderNumber;
            $mindboxChangeBetJob->push();
        }
    }
}