<?php

declare(strict_types=1);

namespace Integra\UserStory\ExternalApplication\Affise\RegisterEvent\Request;

use Integra\Infrastructure\Environment\Env;
use Integra\Infrastructure\Http\Request;
use Integra\Infrastructure\Http\Request\Method;
use Integra\Infrastructure\Http\Request\Method\Get;
use Integra\Infrastructure\Http\Request\Url;
use Integra\Infrastructure\Http\Request\Url\FromString;
use Exception;

/**
 * Class RegisterEventRequest
 *
 * @package Integra\UserStory\ExternalApplication\Affise\RegisterEvent\Request
 */
class RegisterEventRequest implements Request
{
    /**
     * @param string $affiseDeviceId
     * @param string $ubetUserId
     */
    public function __construct(protected string $affiseDeviceId, protected string $ubetUserId) {}

    /**
     * @return array
     */
    public function headers(): array
    {
        return [];
    }

    /**
     * @return string
     */
    public function body(): string
    {
        return '';
    }

    /**
     * @return Method
     */
    public function method(): Method
    {
        return new Get();
    }

    /**
     * @return Url
     *
     * @throws Exception
     */
    public function url(): Url
    {
        return
            new FromString(
                sprintf(
                    '%s/v1/external_data/add_simple_event?affise_device_id=%s&event.affise_parameters.affise_p_customer_user_id=%s&event_name=%s&API-KEY=%s',
                    (new Env('UB_AFFISE_API_URL'))->value(),
                    $this->affiseDeviceId,
                    $this->ubetUserId,
                    'CompleteRegistration',
                    (new Env('UB_AFFISE_API_KEY'))->value(),
                )
            );
    }
}
