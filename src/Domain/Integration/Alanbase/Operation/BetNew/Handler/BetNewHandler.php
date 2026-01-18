<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\Alanbase\Operation\BetNew\Handler;

use Integra\Domain\Integration\Alanbase\AlanbaseConfig;
use Integra\Domain\Integration\Alanbase\Config\AlanbaseOperationConfigInterface;
use Integra\Domain\Integration\Alanbase\Operation\AbstractAlanbaseOperationHandler;
use Integra\Domain\Integration\Alanbase\Operation\BetNew\Data\BetNewDataBuilder;
use Integra\Domain\Integration\Alanbase\Operation\BetNew\Request\BetNewRequest;
use Integra\Domain\Integration\Common\DTOInterface;
use Integra\Domain\Integration\Common\RequestInterface;
use Integra\Domain\Integration\MindBox\Enum\AlanbaseGoalEnum;
use Integra\Domain\Integration\MindBox\Enum\AlanbaseOperationTypeEnum;
use Integra\Infrastructure\Http\Transport;

class BetNewHandler extends AbstractAlanbaseOperationHandler
{
    protected BetNewDataBuilder $builder;

    public function __construct(Transport $transport)
    {
        parent::__construct($transport);
        $this->builder = new BetNewDataBuilder();
    }

    /**
     * @param array $rawParams
     * @return DTOInterface
     */
    protected function buildDTO(array $rawParams): DTOInterface
    {
        return $this->builder->build(
            orderNumber: $rawParams['orderNumber'],
            clickId: $rawParams['clickId'],
        );
    }

    /**
     * @param array $rawParams
     * @return AlanbaseOperationConfigInterface
     * @throws \Exception
     */
    protected function buildConfig(array $rawParams): AlanbaseOperationConfigInterface
    {
        return AlanbaseConfig::create(
            AlanbaseOperationTypeEnum::BET,
            AlanbaseGoalEnum::BET,
            isset($raw['timeoutSeconds']) ? (int)$raw['timeoutSeconds'] : null
        );
    }

    /**
     * @param DTOInterface $dto
     * @param AlanbaseOperationConfigInterface $config
     * @return RequestInterface
     */
    protected function buildRequest(DTOInterface $dto, AlanbaseOperationConfigInterface $config): RequestInterface
    {
        return new BetNewRequest(
            dto: $dto,
            config: $config
        );
    }
}