<?php

declare(strict_types=1);

namespace Integra\UserStory\ExternalApplication\Affise\InstallEvent\Request;

use Integra\Infrastructure\Environment\Env;
use Integra\Infrastructure\Http\Request;
use Integra\Infrastructure\Http\Request\Method;
use Integra\Infrastructure\Http\Request\Method\Get;
use Integra\Infrastructure\Http\Request\Url;
use Integra\Infrastructure\Http\Request\Url\FromString;
use Exception;

/**
 * Class InstallEventRequest
 *
 * @package Integra\UserStory\ExternalApplication\Affise\InstallEvent\Request
 */
class InstallEventRequest implements Request
{
    /**
     * @param string $gaid
     * @param string $affiseDeviceId
     * @param string $randomUserId
     */
    public function __construct(
        protected string $gaid,
        protected string $affiseDeviceId,
        protected string $randomUserId
    ) {}

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
                    '%s/v1/external_data/add_simple_event?gaid=%s&affise_device_id=%s&random_user_id=%s&app_id=%s&first_open_time=%s&API-KEY=%s',
                    (new Env('UB_AFFISE_API_URL'))->value(),
                    $this->gaid,
                    $this->affiseDeviceId,
                    $this->randomUserId,
                    (new Env('UB_AFFISE_APP_ID'))->value(),
                    time(),
                    (new Env('UB_AFFISE_API_KEY'))->value(),
                )
            );
    }
}
