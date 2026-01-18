<?php

declare(strict_types=1);

namespace Integra\UserStory\ExternalApplication\Affise\RegisterEvent;

use Integra\Infrastructure\Generic\Response;
use Integra\Infrastructure\Generic\Response\BadRequest;
use Integra\Infrastructure\Generic\Response\Ok;
use Integra\Infrastructure\Http\Transport;
use Integra\Infrastructure\UserStory;
use Integra\Infrastructure\Validation\Validatable;
use Integra\UserStory\ExternalApplication\Affise\QueryLogTrait;
use Integra\UserStory\ExternalApplication\Affise\RegisterEvent\Request\RegisterEventRequest;
use Integra\UserStory\ExternalApplication\Affise\ResponseErrorTrait;
use Yii;
use yii\db\Exception;

/**
 * Class RegisterEvent
 *
 * @package Integra\UserStory\ExternalApplication\Affise\RegisterEvent
 */
class RegisterEvent implements UserStory
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

        $registerEventResult = $this->transport->response(
            new RegisterEventRequest(
                $validationValue['affise_device_id'],
                $validationValue['ubet_user_id']
            )
        );

        if (!$registerEventResult->isSuccessful()) {
            Yii::error($registerEventResult->error());

            $this->createQueryLog(
                'affise',
                'register',
                true,
                $this->getErrorText($registerEventResult->error()['body'])
            );

            return new BadRequest(['error' => $this->getErrorText($registerEventResult->error()['body'])], []);
        }

        $requestId = $registerEventResult->value()['request_id'] ?? null;

        if (!$requestId) {
            $this->createQueryLog(
                'affise',
                'register',
                true,
                $this->getErrorText('Не получен request_id')
            );

            return new BadRequest(['Не получен request_id'], []);
        }

        $this->createQueryLog(
            'affise',
            'register'
        );

        return
            new Ok([
                'request_id' => $requestId
            ]);
    }
}
