<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\Affise\Operation\Deposit\Handler;

use Integra\Domain\Integration\Affise\AffiseConfig;
use Integra\Domain\Integration\Affise\Config\AffiseOperationConfigInterface;
use Integra\Domain\Integration\Affise\Operation\AbstractAffiseOperationHandler;
use Integra\Domain\Integration\Affise\Operation\Deposit\Data\FirstDepositDataBuilder;
use Integra\Domain\Integration\Affise\Operation\Deposit\Request\DepositRequest;
use Integra\Domain\Integration\Common\DTOInterface;
use Integra\Domain\Integration\Common\RequestInterface;
use Integra\Infrastructure\Http\Transport;

class FirstDepositHandler extends AbstractAffiseOperationHandler
{
    private FirstDepositDataBuilder $builder;

    public function __construct(Transport $transport)
    {
        parent::__construct($transport);
        $this->builder = new FirstDepositDataBuilder();
    }

    /**
     * @param array $rawParams
     * @return DTOInterface
     */
    protected function buildDTO(array $rawParams): DTOInterface
    {
        return $this->builder->build(
            affiseDeviceId: (string)$rawParams['affiseDeviceId'],
            paymentId: (int)$rawParams['paymentId'],
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
        return new DepositRequest(
            dto: $dto,
            config: $config
        );
    }
}