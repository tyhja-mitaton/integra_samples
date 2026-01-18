<?php

namespace Integra\Domain\Integration\AppsFlyer\Operation;

use Integra\Domain\Integration\Common\DTOInterface;
use Integra\Domain\Integration\Common\IntegrationConfigInterface;
use Integra\Domain\Integration\Common\OperationHandlerInterface;
use Integra\Domain\Integration\Common\RequestInterface;
use Integra\Infrastructure\Generic\Result;
use Integra\Infrastructure\Http\Transport;

abstract class AbstractAppsFlyerOperationHandler implements OperationHandlerInterface
{
    public function __construct(
        protected readonly Transport $transport
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function execute(array $rawParams): Result
    {
        $dto = $this->buildDTO($rawParams);
        $config = $this->buildConfig($rawParams);
        $request = $this->buildRequest($dto, $config);
        $result = $this->transport->response($request);
        return $this->returnResult($result, $rawParams);
    }

    /**
     * @param array $raw
     * @return DTOInterface
     */
    abstract protected function buildDTO(array $raw): DTOInterface;

    /**
     * @param array $raw
     * @return IntegrationConfigInterface
     */
    abstract protected function buildConfig(array $raw): IntegrationConfigInterface;

    /**
     * @param DTOInterface $dto
     * @param IntegrationConfigInterface $config
     * @return RequestInterface
     */
    abstract protected function buildRequest(
        DTOInterface               $dto,
        IntegrationConfigInterface $config
    ): RequestInterface;

    /**
     * @param Result $result
     * @param array $raw
     * @return Result
     */
    protected function returnResult(Result $result, array $raw): Result
    {
        return $result;
    }
}