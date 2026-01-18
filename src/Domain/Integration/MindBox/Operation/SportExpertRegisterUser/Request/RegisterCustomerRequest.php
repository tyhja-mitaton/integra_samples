<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\MindBox\Operation\SportExpertRegisterUser\Request;

use Integra\Infrastructure\Http\Request\Method;
use Integra\Domain\Integration\Common\DTOInterface;
use Integra\Infrastructure\Http\Request\Method\Post;
use Integra\Domain\Integration\Common\AbstractRequest;
use Integra\Domain\Integration\Common\RequestInterface;
use Integra\Domain\Integration\Common\IntegrationConfigInterface;

final class RegisterCustomerRequest extends AbstractRequest implements RequestInterface
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
        return [
            'Content-Type' => 'application/json; charset=utf-8',
            'Accept' => 'application/json',
            'Authorization' => 'Mindbox secretKey="' . $this->config->getSecretKey() . '"'
        ];
    }
}
