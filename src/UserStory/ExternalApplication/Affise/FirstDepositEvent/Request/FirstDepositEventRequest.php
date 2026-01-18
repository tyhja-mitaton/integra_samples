<?php

declare(strict_types=1);

namespace Integra\UserStory\ExternalApplication\Affise\FirstDepositEvent\Request;

use Integra\Infrastructure\Environment\Env;
use Integra\Infrastructure\Http\Request;
use Integra\Infrastructure\Http\Request\Method;
use Integra\Infrastructure\Http\Request\Method\Get;
use Integra\Infrastructure\Http\Request\Url;
use Integra\Infrastructure\Http\Request\Url\FromString;
use Exception;

/**
 * Class FirstDepositEventRequest
 *
 * @package Integra\UserStory\ExternalApplication\Affise\FirstDepositEvent\Request
 */
class FirstDepositEventRequest implements Request
{
    /**
     * @param string $affiseDeviceId
     * @param string $price
     * @param string $quantity
     * @param string $revenue
     * @param string $currency
     */
    public function __construct(
        protected string $affiseDeviceId,
        protected string $price,
        protected string $quantity,
        protected string $revenue,
        protected string $currency,
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
                    '%s/v1/external_data/add_simple_event?affise_device_id=%s&p_price=%s&p_quantity=%s&p_revenue=%s&p_currency=%s&event_name=%s&API-KEY=%s',
                    (new Env('UB_AFFISE_API_URL'))->value(),
                    $this->affiseDeviceId,
                    $this->price,
                    $this->quantity,
                    $this->revenue,
                    $this->currency,
                    'CustomId01',
                    (new Env('UB_AFFISE_API_KEY'))->value(),
                )
            );
    }
}
