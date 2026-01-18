<?php

declare(strict_types=1);

namespace Integra\UserStory\ExternalApplication\Affise\BetEvent;

use Integra\Infrastructure\Generic\Response;
use Integra\Infrastructure\Generic\Response\BadRequest;
use Integra\Infrastructure\Generic\Response\Ok;
use Integra\Infrastructure\Http\Transport;
use Integra\Infrastructure\UserStory;
use Integra\Infrastructure\Validation\Validatable;
use Integra\UserStory\ExternalApplication\Affise\BetEvent\Request\BetEventRequest;
use Integra\UserStory\ExternalApplication\Affise\QueryLogTrait;
use Integra\UserStory\ExternalApplication\Affise\ResponseErrorTrait;
use Yii;
use yii\db\Exception;

/**
 * Class BetEvent
 *
 * @package Integra\UserStory\ExternalApplication\Affise\BetEvent
 */
class BetEvent implements UserStory
{
    use ResponseErrorTrait, QueryLogTrait;

    /**
     * @param Validatable $validatable
     * @param Transport $transport
     */
    public function __construct(private Validatable $validatable, private Transport $transport) {}

    /**
     * @return Response
     * @throws Exception
     */
    public function response(): Response
    {
        $validationResult = $this->validatable->result();

        if (!$validationResult->isSuccessful()) {
            return new BadRequest($validationResult->error(), []);
        }

        $validationValue = $validationResult->value();

        $betEventResult = $this->transport->response(
            new BetEventRequest(
                $validationValue['affise_device_id'],
                $validationValue['p_price'],
                $validationValue['p_quantity'],
                $validationValue['p_revenue'],
                $validationValue['p_receipt_id'],
                $validationValue['p_currency']
            )
        );

        if (!$betEventResult->isSuccessful()) {
            Yii::error($betEventResult->error());

            $this->createQueryLog(
                'affise',
                'bet',
                true,
                $this->getErrorText($betEventResult->error()['body'])
            );

            return new BadRequest(['error' => $this->getErrorText($betEventResult->error()['body'])], []);
        }

        $requestId = $betEventResult->value()['request_id'] ?? null;

        if (!$requestId) {
            $this->createQueryLog(
                'affise',
                'bet',
                true,
                'Не получен request_id'
            );

            return new BadRequest(['Не получен request_id'], []);
        }

        $this->createQueryLog(
            'affise',
            'bet'
        );

        return
            new Ok([
                'request_id' => $requestId
            ]);
    }
}
