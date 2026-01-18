<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\Alanbase\Operation\Bonus\Handler;

use Integra\Domain\Integration\Alanbase\AlanbaseConfig;
use Integra\Domain\Integration\Alanbase\Config\AlanbaseOperationConfigInterface;
use Integra\Domain\Integration\Alanbase\Operation\AbstractAlanbaseOperationHandler;
use Integra\Domain\Integration\Alanbase\Operation\Bonus\Data\BonusDataBuilder;
use Integra\Domain\Integration\Alanbase\Operation\Bonus\Request\BonusRequest;
use Integra\Domain\Integration\Common\DTOInterface;
use Integra\Domain\Integration\Common\RequestInterface;
use Integra\Domain\Integration\MindBox\Enum\AlanbaseGoalEnum;
use Integra\Domain\Integration\MindBox\Enum\AlanbaseOperationTypeEnum;
use Integra\Infrastructure\Http\Transport;

class BonusHandler extends AbstractAlanbaseOperationHandler
{
    private BonusDataBuilder $builder;

    public function __construct(Transport $transport)
    {
        parent::__construct($transport);
        $this->builder = new BonusDataBuilder();
    }

    /**
     * @param array $rawParams
     * @return DTOInterface
     */
    protected function buildDTO(array $rawParams): DTOInterface
    {
        return $this->builder->build(
            bonusId: $rawParams['bonusId'],
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
            AlanbaseOperationTypeEnum::BONUS,
            AlanbaseGoalEnum::BONUS,
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
        return new BonusRequest(
            dto: $dto,
            config: $config
        );
    }
}