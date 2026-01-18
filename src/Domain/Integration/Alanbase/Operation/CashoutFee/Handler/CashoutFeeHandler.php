<?php

namespace Integra\Domain\Integration\Alanbase\Operation\CashoutFee\Handler;

use Integra\Domain\Integration\Alanbase\AlanbaseConfig;
use Integra\Domain\Integration\Alanbase\Config\AlanbaseOperationConfigInterface;
use Integra\Domain\Integration\Alanbase\Operation\AbstractAlanbaseOperationHandler;
use Integra\Domain\Integration\Alanbase\Operation\CashoutFee\Data\CashoutFeeDataBuilder;
use Integra\Domain\Integration\Alanbase\Operation\CashoutFee\Request\CashoutFeeRequest;
use Integra\Domain\Integration\Common\DTOInterface;
use Integra\Domain\Integration\Common\RequestInterface;
use Integra\Domain\Integration\MindBox\Enum\AlanbaseGoalEnum;
use Integra\Domain\Integration\MindBox\Enum\AlanbaseOperationTypeEnum;
use Integra\Infrastructure\Http\Transport;

class CashoutFeeHandler extends AbstractAlanbaseOperationHandler
{
    protected CashoutFeeDataBuilder $builder;

    public function __construct(Transport $transport)
    {
        parent::__construct($transport);
        $this->builder = new CashoutFeeDataBuilder();
    }

    /**
     * @inheritDoc
     */
    protected function buildDTO(array $rawParams): DTOInterface
    {
        return $this->builder->build(
            payId: $rawParams['payId'],
            clickId: $rawParams['clickId'],
        );
    }

    /**
     * @inheritDoc
     */
    protected function buildConfig(array $rawParams): AlanbaseOperationConfigInterface
    {
        return AlanbaseConfig::create(
            AlanbaseOperationTypeEnum::CASHOUT_FEE,
            AlanbaseGoalEnum::CASHOUT_FEE,
            isset($raw['timeoutSeconds']) ? (int)$raw['timeoutSeconds'] : null
        );
    }

    /**
     * @inheritDoc
     */
    protected function buildRequest(DTOInterface $dto, AlanbaseOperationConfigInterface $config): RequestInterface
    {
        return new CashoutFeeRequest(
            dto: $dto,
            config: $config
        );
    }
}