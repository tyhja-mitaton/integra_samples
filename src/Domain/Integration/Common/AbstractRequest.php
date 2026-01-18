<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\Common;

use GuzzleHttp\RequestOptions;
use Integra\Infrastructure\Http\Request\Url;
use Integra\Infrastructure\Http\Request\Method;
use Integra\Infrastructure\Http\Request\Method\Get;

abstract class AbstractRequest implements RequestInterface
{
    public function __construct(
        protected readonly DTOInterface               $dto,
        protected readonly IntegrationConfigInterface $config,
    )
    {
    }

    /**
     * @return Url
     */
    public function url(): Url
    {
        return $this->config->getUrl($this->dto);
    }

    /**
     * @return array
     */
    abstract public function headers(): array;

    /**
     * @return string
     */
    public function body(): string
    {
        return '';
    }

    /**
     * @return array
     */
    public function options(): array
    {
        $headers = $this->headers();
        $timeout = $this->getConfig()->getTimeoutSeconds();
        $data = $this->getDTO()->toArray();

        if ($this->method() instanceof Get) {
            return [
                RequestOptions::HEADERS => $headers,
                RequestOptions::TIMEOUT => $timeout,
            ];
        }

        return [
            RequestOptions::JSON => $data,
            RequestOptions::HEADERS => $headers,
            RequestOptions::TIMEOUT => $timeout,
        ];
    }

    /**
     * @return DTOInterface
     */
    abstract public function getDTO(): DTOInterface;

    /**
     * @return IntegrationConfigInterface
     */
    abstract public function getConfig(): IntegrationConfigInterface;

    /**
     * @return Method
     */
    abstract public function method(): Method;

}
