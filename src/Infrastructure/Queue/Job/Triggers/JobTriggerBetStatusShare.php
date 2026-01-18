<?php

namespace Integra\Infrastructure\Queue\Job\Triggers;

use Integra\Domain\Services\AdjustService;
use Integra\Domain\Services\AlanbaseService;
use Integra\Domain\Services\MindBoxService;
use Integra\Infrastructure\Queue\BaseJob;
use Integra\Infrastructure\Queue\Job\Integrations\Adjust\AdjustDebitByBatchJob;
use Integra\Infrastructure\Queue\Job\Integrations\Alanbase\AlanbaseBetBonusFeeJob;
use Integra\Infrastructure\Queue\Job\Integrations\Alanbase\AlanbaseBetWinJob;
use Integra\Infrastructure\Queue\Job\Integrations\Alanbase\AlanbaseBetWinMiniGameJob;
use Integra\Infrastructure\Queue\Job\Integrations\MindBox\MindBoxBetStatusJob;
use Integra\Infrastructure\Queue\Job\Integrations\MindBox\MindBoxMiniGameBetStatusJob;
use Integra\Infrastructure\Queue\Job\Integrations\Promo\PromoBetResultBetJob;
use Integra\Infrastructure\Queue\Job\Integrations\Promo\PromoBetResultTurnoverJob;
use Integra\Models\Ubet\GameBet;
use Integra\Models\Ubet\SportBet;
use Integra\Models\Ubet\TransactionBonus;
use yii\helpers\StringHelper;
use yii\queue\Queue;
use Yii;

class JobTriggerBetStatusShare extends BaseJob
{
    public int $betId;
    public ?int $partnerId;
    public ?int $offerId;
    private AlanbaseService $alanbaseService;
    private MindBoxService $mindBoxService;
    private AdjustService $adjustService;

    public function init(): void
    {
        parent::init();
        $this->alanbaseService = new AlanbaseService();
        $this->mindBoxService = new MindBoxService();
        $this->adjustService = new AdjustService();
    }

    /**
     * @inheritDoc
     */
    protected function handle(Queue $queue): void
    {
        $this->mindboxTrigger();
        $this->alnanbaseTrigger();
        $this->promoTrigger();
        $this->adjustTrigger();
    }

    private function mindboxTrigger():void
    {
        $bet = SportBet::findOne(['bet_id' => $this->betId]);

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
                $mindboxBetStatusJob = new MindBoxBetStatusJob();
                $mindboxBetStatusJob->betId = $this->betId;
            } else {
                $mindboxBetStatusJob = new MindBoxMiniGameBetStatusJob();
                $mindboxBetStatusJob->gameId = $this->betId;
            }
            $mindboxBetStatusJob->push();
        }
    }

    private function alnanbaseTrigger()
    {
        if(!$this->isMiniGame) {
            $bet = SportBet::findOne(['bet_id' => $this->betId]);
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
                $job = new AlanbaseBetWinJob();
                $job->clickId = $data->clickId;
                $job->betId = $bet->bet_id;

                if (!empty($bet->bonus)) {
                    $transaction = TransactionBonus::findOne(['transaction_id' => $bet->transaction_id]);

                    if ($transaction && $transaction->amount_real != 0) {
                        $job = new AlanbaseBetBonusFeeJob();
                        $job->clickId = $data->clickId;
                        $job->betId = $bet->bet_id;
                        $job->push();
                    }
                }
            } else {
                $job = new AlanbaseBetWinMiniGameJob();
                $job->clickId = $data->clickId;
                $job->gameId = $bet->bet_id;
            }
            $job->push();
        }
    }

    private function promoTrigger(): void
    {
        if(!$this->isMiniGame) {
            $job = new PromoBetResultTurnoverJob();
            $job->betId = $this->betId;
            $job->push();

            $job = new PromoBetResultBetJob();
            $job->betId = $this->betId;
            $job->push();
        }
    }

    private function adjustTrigger(): void
    {
        if(!$this->isMiniGame) {
            $bet = SportBet::findOne(['bet_id' => $this->betId]);
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

            $job = new AdjustDebitByBatchJob();
            $job->userId = $bet->user_id;
            $job->betId = $bet->bet_id;
            $job->partnerId = $this->partnerId;
            $job->offerId = $this->offerId;
            $job->push();
        }
    }
}