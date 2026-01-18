<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\Alanbase\Operation;

use Integra\Infrastructure\Generic\Result;
use Integra\Infrastructure\Http\Transport;
use Integra\Domain\Integration\Common\DTOInterface;
use Integra\Domain\Integration\Common\RequestInterface;
use Integra\Domain\Integration\Common\OperationHandlerInterface;
use Integra\Domain\Integration\Alanbase\Config\AlanbaseOperationConfigInterface;

abstract class AbstractAlanbaseOperationHandler implements OperationHandlerInterface
{
    public function __construct(
        protected readonly Transport $transport)
    {
    }

    /**
     * @param array $rawParams
     * @return Result
     */
    public function execute(array $rawParams): Result
    {
        $dto = $this->buildDTO($rawParams);
        $config = $this->buildConfig($rawParams);
        $request = $this->buildRequest($dto, $config);


        return $this->transport->response($request);
    }

    /**
     * @param array $rawParams
     * @return DTOInterface
     */
    abstract protected function buildDTO(array $rawParams): DTOInterface;

    /**
     * @param array $rawParams
     * @return AlanbaseOperationConfigInterface
     */
    abstract protected function buildConfig(array $rawParams): AlanbaseOperationConfigInterface;

    /**
     * @param DTOInterface $dto
     * @param AlanbaseOperationConfigInterface $config
     * @return RequestInterface
     */
    abstract protected function buildRequest(DTOInterface $dto, AlanbaseOperationConfigInterface $config): RequestInterface;

    /**
     * @param Result $result
     * @param array $rawParams
     * @return Result
     */
    protected function returnResult(Result $result, array $rawParams): Result
    {
        return $result;
    }
}
