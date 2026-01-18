<?php

declare(strict_types=1);

namespace Integra\Controller\Console;

use Integra\Infrastructure\Console\Message\FromResult;
use Integra\Infrastructure\Queue\Job\Integrations\MindBox\MindBoxBetChangeJob;
use Integra\Infrastructure\Queue\Job\Integrations\MindBox\MindBoxBetJob;
use Integra\Infrastructure\Queue\Job\Integrations\MindBox\MindBoxBetStatusJob;
use Integra\Infrastructure\Queue\Job\Integrations\MindBox\MindBoxBonusJob;
use Integra\Infrastructure\Queue\Job\Integrations\MindBox\MindBoxBonusStatusJob;
use Integra\Infrastructure\Queue\Job\Integrations\MindBox\MindBoxDepositJob;
use Integra\Infrastructure\Queue\Job\Integrations\MindBox\MindBoxDepositStatusJob;
use Integra\UserStory\Console\AppToken\CreateAppToken;
use yii\console\Controller;
use yii\console\ExitCode;

class AppTokenController extends Controller
{
    /**
     * Добавление токена(app_token) для внешних приложений. Аргумент: string applicationName, string token(20+ chars)
     */
    public function actionCreate(string $application, string $token)
    {
        $result = (new CreateAppToken($application, $token))->run();

        echo (new FromResult($result))->value();

        if (!$result->isSuccessful()) {
            return ExitCode::DATAERR;
        }

        return ExitCode::OK;
    }

    public function actionTest()
    {
        $mindboxDepositJob = new MindBoxBonusStatusJob();
        $mindboxDepositJob->bonusId = 5873533;//207;// 53074569;
        $mindboxDepositJob->statusId = 1;
        $mindboxDepositJob->execute(\Yii::$app->queue_triggers_mindbox);
        /*try {
            $mindboxDepositJob->push();    } catch (\Exception $exception) {        echo $exception->getMessage();    }*/
        echo 'done!';
    }
}