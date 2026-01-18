<?php

declare(strict_types=1);

namespace Integra\UserStory\ExternalApplication\Affise\DepositEvent;

use Integra\Infrastructure\Generic\Response;
use Integra\Infrastructure\Generic\Response\BadRequest;
use Integra\Infrastructure\Generic\Response\Ok;
use Integra\Infrastructure\Http\Transport;
use Integra\Infrastructure\UserStory;
use Integra\Infrastructure\Validation\Validatable;
use Integra\UserStory\ExternalApplication\Affise\DepositEvent\Request\DepositEventRequest;
use Integra\UserStory\ExternalApplication\Affise\QueryLogTrait;
use Integra\UserStory\ExternalApplication\Affise\ResponseErrorTrait;
use Yii;
use yii\db\Exception;

/**
 * Class DepositEvent
 *
 * @package Integra\UserStory\ExternalApplication\Affise\DepositEvent
 */
class DepositEvent implements UserStory
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

        $depositEventResult = $this->transport->response(
            new DepositEventRequest(
                $validationValue['affise_device_id'],
                $validationValue['p_price'],
                $validationValue['p_quantity'],
                $validationValue['p_revenue'],
                $validationValue['p_currency']
            )
        );

        if (!$depositEventResult->isSuccessful()) {
            Yii::error($depositEventResult->error());

            $this->createQueryLog(
                'affise',
                'deposit',
                true,
                $this->getErrorText($depositEventResult->error()['body'])
            );

            return new BadRequest(['error' => $this->getErrorText($depositEventResult->error()['body'])], []);
        }

        $requestId = $depositEventResult->value()['request_id'] ?? null;

        if (!$requestId) {
            $this->createQueryLog(
                'affise',
                'deposit',
                true,
                $this->getErrorText('Не получен request_id')
            );

            return new BadRequest(['Не получен request_id'], []);
        }

        $this->createQueryLog(
            'affise',
            'deposit'
        );

        return
            new Ok([
                'request_id' => $requestId
            ]);
    }
}
