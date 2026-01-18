<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\Alanbase\Operation\RegisterLead\Handler;

use Exception;
use Integra\Infrastructure\Http\Transport;
use Integra\Domain\Integration\Common\DTOInterface;
use Integra\Domain\Integration\Common\RequestInterface;
use Integra\Domain\Integration\Alanbase\AlanbaseConfig;
use Integra\Domain\Integration\MindBox\Enum\AlanbaseGoalEnum;
use Integra\Domain\Integration\MindBox\Enum\AlanbaseOperationTypeEnum;
use Integra\Domain\Integration\Alanbase\Config\AlanbaseOperationConfigInterface;
use Integra\Domain\Integration\Alanbase\Operation\AbstractAlanbaseOperationHandler;
use Integra\Domain\Integration\Alanbase\Operation\RegisterLead\Request\RegisterGoalRequest;
use Integra\Domain\Integration\Alanbase\Operation\RegisterGoal\Data\RegisterGoalDataBuilder;

final class RegisterGoalHandler extends AbstractAlanbaseOperationHandler
{
    private RegisterGoalDataBuilder $builder;

    public function __construct(Transport $transport)
    {
        parent::__construct($transport);
        $this->builder = new RegisterGoalDataBuilder();
    }

    /**
     * @param array $rawParams
     * @return DTOInterface
     * @throws Exception
     */
    protected function buildDTO(array $rawParams): DTOInterface
    {
        return $this->builder->build(
            userId: $rawParams['userId'],
            clickId: $rawParams['clickId'],
        );
    }

    /**
     * @param array $rawParams
     * @return AlanbaseOperationConfigInterface
     * @throws Exception
     */
    protected function buildConfig(array $rawParams): AlanbaseOperationConfigInterface
    {
        return AlanbaseConfig::create(
            AlanbaseOperationTypeEnum::GOAL,
            AlanbaseGoalEnum::REGISTRATION,
            isset($raw['timeoutSeconds']) ? (int)$raw['timeoutSeconds'] : null
        );
    }

    /**
     * @param DTOInterface $dto
     * @param AlanbaseOperationConfigInterface $config
     * @return RequestInterface
     */
    protected function buildRequest(
        DTOInterface                     $dto,
        AlanbaseOperationConfigInterface $config
    ): RequestInterface
    {
        return new RegisterGoalRequest(
            dto: $dto,
            config: $config
        );
    }
}
