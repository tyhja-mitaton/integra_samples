<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\Affise\Operation\Bet\Handler;

use Integra\Domain\Integration\Affise\AffiseConfig;
use Integra\Domain\Integration\Affise\Config\AffiseOperationConfigInterface;
use Integra\Domain\Integration\Affise\Operation\AbstractAffiseOperationHandler;
use Integra\Domain\Integration\Affise\Operation\Bet\Data\BetDataBuilder;
use Integra\Domain\Integration\Affise\Operation\Bet\Request\BetRequest;
use Integra\Domain\Integration\Common\DTOInterface;
use Integra\Domain\Integration\Common\RequestInterface;
use Integra\Infrastructure\Http\Transport;

final class BetHandler extends AbstractAffiseOperationHandler
{
    private BetDataBuilder $builder;

    public function __construct(Transport $transport)
    {
        parent::__construct($transport);
        $this->builder = new BetDataBuilder();
    }

    /**
     * @param array $rawParams
     * @return DTOInterface
     */
    protected function buildDTO(array $rawParams): DTOInterface
    {
        return $this->builder->build(
            affiseDeviceId: (string)$rawParams['affiseDeviceId'],
            betSum: (int)$rawParams['betSum'],
            receiptId: $rawParams['receiptId'],
        );
    }

    /**
     * @param array $rawParams
     * @return AffiseOperationConfigInterface
     * @throws \Exception
     */
    protected function buildConfig(array $rawParams): AffiseOperationConfigInterface
    {
        return AffiseConfig::create(
            isset($raw['timeoutSeconds']) ? (int)$raw['timeoutSeconds'] : null
        );
    }

    /**
     * @param DTOInterface $dto
     * @param AffiseOperationConfigInterface $config
     * @return RequestInterface
     */
    protected function buildRequest(DTOInterface $dto, AffiseOperationConfigInterface $config): RequestInterface
    {
        return new BetRequest(
            dto: $dto,
            config: $config
        );
    }
}