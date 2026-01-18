<?php

declare(strict_types=1);

namespace Integra\Controller\Api\V1;

use GuzzleHttp\Client;
use Integra\Infrastructure\Http\Transport\DefaultHttpTransport;
use Integra\Infrastructure\Http\Transport\JsonDecoded;
use Integra\Infrastructure\UserStory;
use Integra\UserStory\ExternalApplication\Affise\BetEvent\BetEvent;
use Integra\UserStory\ExternalApplication\Affise\BetEvent\Validation\ValidatedBetEventCommand;
use Integra\UserStory\ExternalApplication\Affise\DepositEvent\DepositEvent;
use Integra\UserStory\ExternalApplication\Affise\FirstDepositEvent\FirstDepositEvent;
use Integra\UserStory\ExternalApplication\Affise\DepositEvent\Validation\ValidatedDepositEventCommand;
use Integra\UserStory\ExternalApplication\Affise\FirstDepositEvent\Validation\ValidatedFirstDepositEventCommand;
use Integra\UserStory\ExternalApplication\Affise\RegisterEvent\RegisterEvent;
use Integra\UserStory\ExternalApplication\Affise\RegisterEvent\Validation\ValidatedRegisterEventCommand;
use Integra\UserStory\ExternalApplication\Affise\InstallEvent\InstallEvent;
use Integra\UserStory\ExternalApplication\Affise\InstallEvent\Validation\ValidatedInstallEventCommand;
use Yii;
use yii\rest\Controller;

/**
 * Class AffiseEventController
 *
 * @package Integra\Controller\Api\V1
 */
class AffiseEventController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/events/install",
     *     operationId="actionInstall",
     *     summary="Installing new device",
     *     tags={"InstallEvent"},
     *     @OA\Parameter(
     *         name="gaid",
     *         in="query",
     *         description="",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="affise_device_id",
     *         in="query",
     *         description="",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="random_user_id",
     *         in="query",
     *         description="",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             example=
     *             {
     *                 "result": {
     *                     "successful": true,
     *                     "code": 200
     *                 },
     *                 "payload": {
     *                      "request_id": "131b6fcf79d4368ce6523ba143bcccb3",
     *                  }
     *              }
     *         ),
     *     )
     * )
     */
    public function actionInstall(): UserStory
    {
        return new InstallEvent(
            new ValidatedInstallEventCommand(
                Yii::$app->request->getQueryParams(),
            ),
            new JsonDecoded(
                new DefaultHttpTransport(
                    new Client(['http_errors' => false])
                )
            )
        );
    }

    /**
     * @OA\Get(
     *     path="/api/v1/events/register",
     *     operationId="actionRegister",
     *     summary="Completing registration",
     *     tags={"RegisterEvent"},
     *     @OA\Parameter(
     *         name="affise_device_id",
     *         in="query",
     *         description="",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="random_user_id",
     *         in="query",
     *         description="",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="ubet_user_id",
     *         in="query",
     *         description="",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             example=
     *             {
     *                 "result": {
     *                     "successful": true,
     *                     "code": 200
     *                 },
     *                 "payload": {
     *                      "request_id": "131b6fcf79d4368ce6523ba143bcccb3",
     *                  }
     *              }
     *         ),
     *     )
     * )
     */
    public function actionRegister(): UserStory
    {
        return new RegisterEvent(
            new ValidatedRegisterEventCommand(
                Yii::$app->request->getQueryParams(),
            ),
            new JsonDecoded(
                new DefaultHttpTransport(
                    new Client(['http_errors' => false])
                )
            )
        );
    }

    /**
     * @OA\Get(
     *     path="/api/v1/events/deposit",
     *     operationId="actionDeposit",
     *     summary="Deposit event",
     *     tags={"DepositEvent"},
     *     @OA\Parameter(
     *         name="affise_device_id",
     *         in="query",
     *         description="",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="p_price",
     *         in="query",
     *         description="",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="p_quantity",
     *         in="query",
     *         description="",
     *         required=true,
     *         @OA\Schema(type="integer")
     *      ),
     *     @OA\Parameter(
     *         name="p_revenue",
     *         in="query",
     *         description="",
     *         required=true,
     *         @OA\Schema(type="integer")
     *      ),
     *     @OA\Parameter(
     *         name="p_currency",
     *         in="query",
     *         description="",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             example=
     *             {
     *                 "result": {
     *                     "successful": true,
     *                     "code": 200
     *                 },
     *                 "payload": {
     *                      "request_id": "131b6fcf79d4368ce6523ba143bcccb3",
     *                  }
     *              }
     *         ),
     *     )
     * )
     */
    public function actionDeposit(): UserStory
    {
        return new DepositEvent(
            new ValidatedDepositEventCommand(
                Yii::$app->request->getQueryParams(),
            ),
            new JsonDecoded(
                new DefaultHttpTransport(
                    new Client(['http_errors' => false])
                )
            )
        );
    }

    /**
     * @OA\Get(
     *     path="/api/v1/events/first-deposit",
     *     operationId="actionFirstDeposit",
     *     summary="First Deposit event",
     *     tags={"FirstDepositEvent"},
     *     @OA\Parameter(
     *         name="affise_device_id",
     *         in="query",
     *         description="",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="p_price",
     *         in="query",
     *         description="",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="p_quantity",
     *         in="query",
     *         description="",
     *         required=true,
     *         @OA\Schema(type="integer")
     *      ),
     *     @OA\Parameter(
     *         name="p_revenue",
     *         in="query",
     *         description="",
     *         required=true,
     *         @OA\Schema(type="integer")
     *      ),
     *     @OA\Parameter(
     *         name="p_currency",
     *         in="query",
     *         description="",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             example=
     *             {
     *                 "result": {
     *                     "successful": true,
     *                     "code": 200
     *                 },
     *                 "payload": {
     *                      "request_id": "131b6fcf79d4368ce6523ba143bcccb3",
     *                  }
     *              }
     *         ),
     *     )
     * )
     */
    public function actionFirstDeposit(): UserStory
    {
        return new FirstDepositEvent(
            new ValidatedFirstDepositEventCommand(
                Yii::$app->request->getQueryParams(),
            ),
            new JsonDecoded(
                new DefaultHttpTransport(
                    new Client(['http_errors' => false])
                )
            )
        );
    }

    /**
     * @OA\Get(
     *     path="/api/v1/events/bet",
     *     operationId="actionBet",
     *     summary="Bet event",
     *     tags={"BetEvent"},
     *     @OA\Parameter(
     *         name="affise_device_id",
     *         in="query",
     *         description="",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="p_price",
     *         in="query",
     *         description="",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="p_quantity",
     *         in="query",
     *         description="",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="p_revenue",
     *         in="query",
     *         description="",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="p_receipt_id",
     *         in="query",
     *         description="",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="p_currency",
     *         in="query",
     *         description="",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             example=
     *             {
     *                 "result": {
     *                     "successful": true,
     *                     "code": 200
     *                 },
     *                 "payload": {
     *                      "request_id": "131b6fcf79d4368ce6523ba143bcccb3",
     *                  }
     *              }
     *         ),
     *     )
     * )
     */
    public function actionBet(): UserStory
    {
        return new BetEvent(
            new ValidatedBetEventCommand(
                Yii::$app->request->getQueryParams(),
            ),
            new JsonDecoded(
                new DefaultHttpTransport(
                    new Client(['http_errors' => false])
                )
            )
        );
    }
}
