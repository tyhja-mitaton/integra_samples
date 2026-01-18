<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\MindBox\Operation;

use Integra\Infrastructure\Http\Transport;
use Integra\Infrastructure\Generic\Result;
use Integra\Domain\Integration\Common\DTOInterface;
use Integra\Domain\Integration\Common\RequestInterface;
use Integra\Domain\Integration\Common\IntegrationConfigInterface;
use Integra\Domain\Integration\Common\OperationHandlerInterface;

abstract class AbstractMindBoxOperationHandler implements OperationHandlerInterface
{
    public function __construct(
        protected readonly Transport $transport
    )
    {
    }

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
     * Можно переопределить в handler наследнике, если нужно возвратить модифицированный результат
     * @param Result $result
     * @param array $raw
     * @return Result
     */
    protected function returnResult(Result $result, array $raw): Result
    {
        return $result;
    }
}
