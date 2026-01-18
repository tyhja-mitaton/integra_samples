<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\Promo\Operation\Deposit\Request;

use Integra\Domain\Integration\Common\AbstractRequest;
use Integra\Domain\Integration\Common\DTOInterface;
use Integra\Domain\Integration\Common\IntegrationConfigInterface;
use Integra\Domain\Integration\Common\RequestInterface;
use Integra\Infrastructure\Http\Request\Method;
use Integra\Infrastructure\Http\Request\Method\Post;

class DepositPromoRequest extends AbstractRequest implements RequestInterface
{
    /**
     * @return Method
     */
    public function method(): Method
    {
        return new Post;
    }

    /**
     * @return DTOInterface
     */
    public function getDTO(): DTOInterface
    {
        return $this->dto;
    }

    /**
     * @return IntegrationConfigInterface
     */
    public function getConfig(): IntegrationConfigInterface
    {
        return $this->config;
    }

    /**
     * @return string[]
     */
    public function headers(): array
    {
        return ['Content-Type' => 'application/json'];
    }
}