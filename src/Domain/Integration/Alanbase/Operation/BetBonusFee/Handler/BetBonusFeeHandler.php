<?php

namespace Integra\Domain\Integration\Alanbase\Operation\BetBonusFee\Handler;

use Integra\Domain\Integration\Alanbase\AlanbaseConfig;
use Integra\Domain\Integration\Alanbase\Config\AlanbaseOperationConfigInterface;
use Integra\Domain\Integration\Alanbase\Operation\AbstractAlanbaseOperationHandler;
use Integra\Domain\Integration\Alanbase\Operation\BetBonusFee\Request\BetBonusFeeRequest;
use Integra\Domain\Integration\Alanbase\Operation\BonusFee\Data\BetBonusFeeDataBuilder;
use Integra\Domain\Integration\Common\DTOInterface;
use Integra\Domain\Integration\Common\RequestInterface;
use Integra\Domain\Integration\MindBox\Enum\AlanbaseGoalEnum;
use Integra\Domain\Integration\MindBox\Enum\AlanbaseOperationTypeEnum;
use Integra\Infrastructure\Http\Transport;

class BetBonusFeeHandler extends AbstractAlanbaseOperationHandler
{
    protected BetBonusFeeDataBuilder $builder;

    public function __construct(Transport $transport)
    {
        parent::__construct($transport);
        $this->builder = new BetBonusFeeDataBuilder();
    }

    /**
     * @inheritDoc
     */
    protected function buildDTO(array $rawParams): DTOInterface
    {
        return $this->builder->build(
            betId: $rawParams['betId'],
            clickId: $rawParams['clickId'],
        );
    }

    /**
     * @inheritDoc
     */
    protected function buildConfig(array $rawParams): AlanbaseOperationConfigInterface
    {
        return AlanbaseConfig::create(
            AlanbaseOperationTypeEnum::BONUS_FEE,
            AlanbaseGoalEnum::BONUS_FEE,
            isset($raw['timeoutSeconds']) ? (int)$raw['timeoutSeconds'] : null
        );
    }

    /**
     * @inheritDoc
     */
    protected function buildRequest(DTOInterface $dto, AlanbaseOperationConfigInterface $config): RequestInterface
    {
        return new BetBonusFeeRequest(
            dto: $dto,
            config: $config
        );
    }
}