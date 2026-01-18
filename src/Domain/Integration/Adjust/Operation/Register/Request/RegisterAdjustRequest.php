<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\Adjust\Operation\Register\Request;

use Integra\Infrastructure\Http\Request\Method;
use Integra\Domain\Integration\Common\DTOInterface;
use Integra\Infrastructure\Http\Request\Method\Post;
use Integra\Domain\Integration\Common\AbstractRequest;
use Integra\Domain\Integration\Common\RequestInterface;
use Integra\Domain\Integration\Common\IntegrationConfigInterface;

final class RegisterAdjustRequest extends AbstractRequest implements RequestInterface
{
    public function method(): Method
    {
        return new Post;
    }

    public function getDTO(): DTOInterface
    {
        return $this->dto;
    }

    public function getConfig(): IntegrationConfigInterface
    {
        return $this->config;
    }

    public function headers(): array
    {
        return [
            'Content-Type: application/x-www-form-urlencoded; charset=utf-8',
            'Authorization: Bearer ' . $this->config->getBearerToken(),
        ];
    }
}