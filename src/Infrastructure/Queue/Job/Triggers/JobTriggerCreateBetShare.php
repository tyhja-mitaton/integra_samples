<?php

namespace Integra\Infrastructure\Queue\Job\Triggers;

use Integra\Domain\Services\AdjustService;
use Integra\Domain\Services\AffiseService;
use Integra\Domain\Services\AlanbaseService;
use Integra\Domain\Services\MindBoxService;
use Integra\Infrastructure\Queue\BaseJob;
use Integra\Infrastructure\Queue\Job\Integrations\Adjust\AdjustCreditBetJob;
use Integra\Infrastructure\Queue\Job\Integrations\Affise\AffiseBetJob;
use Integra\Infrastructure\Queue\Job\Integrations\Alanbase\AlanbaseBetMiniGameJob;
use Integra\Infrastructure\Queue\Job\Integrations\Alanbase\AlanbaseBetNewJob;
use Integra\Infrastructure\Queue\Job\Integrations\MindBox\MindBoxBetJob;
use Integra\Infrastructure\Queue\Job\Integrations\MindBox\MindBoxMiniGameBetJob;
use Integra\Infrastructure\Queue\Job\Integrations\Promo\PromoBetJob;
use Integra\Models\Ubet\GameBet;
use Integra\Models\Ubet\SportBet;
use yii\helpers\StringHelper;
use yii\queue\Queue;
use Yii;

class JobTriggerCreateBetShare extends BaseJob
{
    public int $betId;
    public ?bool $isMiniGame = false;
    public ?int $partnerId;
    public ?int $offerId;

    private AlanbaseService $alanbaseService;
    private MindBoxService $mindBoxService;
    private AffiseService $affiseService;
    private AdjustService $adjustService;

    public function init(): void
    {
        parent::init();
        $this->alanbaseService = new AlanbaseService();
        $this->mindBoxService = new MindBoxService();
        $this->affiseService = new AffiseService();
        $this->adjustService = new AdjustService();
    }

    /**
     * @inheritDoc
     */
    protected function handle(Queue $queue): void
    {
        $this->alnanbaseTrigger();
        $this->mindboxTrigger();
        $this->affiseTrigger();
        $this->promoTrigger();
    }

    private function mindboxTrigger():void
    {
        if(!$this->isMiniGame) {
            $bet = SportBet::findOne(['bet_id' => $this->betId, 'tr_name' => 'CreditBet']);
        } else {
            $bet = GameBet::findOne(['bet_id' => $this->betId]);
        }

        if (!$bet) {
            $message = sprintf(
                '[%s] Bet not found:: %s',
                StringHelper::basename(self::class),
                $this->betId
            );
            Yii::error($message, __METHOD__);

            return;
        }

        if ($this->mindBoxService->reactivateUserIfNotExistMindbox($bet->user_id, true)) {
            if(!$this->isMiniGame) {
                $mindboxBetJob = new MindBoxBetJob();
                $mindboxBetJob->orderNumber = $bet->order_number;
                $mindboxBetJob->push();
            } else {
                $mindboxBetJob = new MindBoxMiniGameBetJob();
                $mindboxBetJob->gameId = $bet->bet_id;
                $mindboxBetJob->push();
            }
        }
    }

    private function alnanbaseTrigger()
    {
        if(!$this->isMiniGame) {
            $bet = SportBet::findOne(['bet_id' => $this->betId, 'tr_name' => 'CreditBet']);
        } else {
            $bet = GameBet::findOne(['bet_id' => $this->betId]);
        }

        if(empty($bet)) {
            $message = sprintf(
                '[%s] Bet not found:: betId=%s; isMiniGame=%s',
                StringHelper::basename(static::class),
                $this->betId,
                $this->isMiniGame
            );
            Yii::error($message, __METHOD__);

            return;
        }

        if ($this->alanbaseService->isAlanbaseUser($bet->user_id)) {
            $data = $this->alanbaseService->getAlanbaseDataByUserId($bet->user_id);

            if(!$this->isMiniGame) {
                $job = new AlanbaseBetNewJob();
                $job->clickId = $data->clickId;
                $job->orderNumber = $bet->order_number;
                $job->push();
            } else {
                $job = new AlanbaseBetMiniGameJob();
                $job->clickId = $data->clickId;
                $job->gameId = $bet->bet_id;
                $job->push();
            }
        }
    }

    private function affiseTrigger(): void
    {
        if(!$this->isMiniGame) {
            $bet = SportBet::findOne(['bet_id' => $this->betId, 'tr_name' => 'CreditBet']);
        } else {
            $bet = GameBet::findOne(['bet_id' => $this->betId]);
        }

        if ($this->affiseService->isAffiseUser($bet->user_id)) {
            $affiseData = $this->affiseService->getAffiseDataByUserId($bet->user_id);

            $job = new AffiseBetJob();
            $job->affiseDeviceId = $affiseData->affiseDeviceId;
            $job->userId = $affiseData->userId;
            $job->betSum = $bet->bet_sum;
            $job->receiptId = $bet->order_number;
            $job->push();
        }
    }

    private function promoTrigger(): void
    {
        if(!$this->isMiniGame) {
            $bet = SportBet::findOne(['bet_id' => $this->betId, 'tr_name' => 'CreditBet']);
        } else {
            $bet = GameBet::findOne(['bet_id' => $this->betId]);
        }

        if (!$this->isMiniGame && $bet->bet_sum >= 10000 && $bet->factor >= 1.3) {
            $job = new PromoBetJob();
            $job->betId = $this->betId;
            $job->push();
        }
    }

    private function adjustTrigger(): void
    {
        if(!$this->isMiniGame) {
            $bet = SportBet::findOne(['bet_id' => $this->betId, 'tr_name' => 'CreditBet']);
        } else {
            $bet = GameBet::findOne(['bet_id' => $this->betId]);
        }
        if(empty($bet)) {
            $message = sprintf(
                '[%s] Bet not found:: betId=%s; isMiniGame=%s',
                StringHelper::basename(static::class),
                $this->betId,
                $this->isMiniGame
            );
            Yii::error($message, __METHOD__);

            return;
        }

        if (!$this->isMiniGame && $bet->bonus == 0 && $bet->bet_win_real != 0) {
            $alanbaseDataForAdjust = $this->adjustService->isAlanbaseUser($bet->user_id)
                ? $this->adjustService->getAlanbaseDataByUserId($bet->user_id)
                : null;

            if (empty($alanbaseDataForAdjust)) {
                $message = sprintf(
                    '[%s] user NOT success CheckAlanbaseUser. userId=%s: , betId=%s.',
                    StringHelper::basename(static::class),
                    $bet->user_id,
                    $this->betId
                );
                Yii::info($message, __METHOD__);
            }

            $job = new AdjustCreditBetJob();
            $job->userId = $bet->user_id;
            $job->orderNumber = $bet->order_number;
            $job->partnerId = $this->partnerId;
            $job->offerId = $this->offerId;
            $job->push();
        }
    }
}