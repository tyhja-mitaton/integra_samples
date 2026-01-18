<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\MindBox\Operation\CreateBet\Handler;

use Integra\Domain\Integration\Common\DTOInterface;
use Integra\Domain\Integration\Common\IntegrationConfigInterface;
use Integra\Domain\Integration\Common\RequestInterface;
use Integra\Domain\Integration\MindBox\Enum\MindBoxOperationNameEnum;
use Integra\Domain\Integration\MindBox\MindBoxConfig;
use Integra\Domain\Integration\MindBox\Operation\AbstractMindBoxOperationHandler;
use Integra\Domain\Integration\MindBox\Operation\CreateBet\Data\CreateBetMiniGameDataBuilder;
use Integra\Domain\Integration\MindBox\Operation\CreateBet\Request\CreateBetRequest;
use Integra\Infrastructure\Http\Transport;

class CreateBetMiniGameHandler extends AbstractMindBoxOperationHandler
{
    private CreateBetMiniGameDataBuilder $builder;

    public function __construct(
        Transport                    $transport
    )
    {
        parent::__construct($transport);
        $this->builder = new CreateBetMiniGameDataBuilder();
    }

    /**
     * @param array $raw
     * @return DTOInterface
     */
    protected function buildDTO(array $raw): DTOInterface
    {
        return $this->builder->build(
            gameId: (int)$raw['gameId'],
        );
    }

    /**
     * @param array $raw
     * @return IntegrationConfigInterface
     * @throws \Exception
     */
    protected function buildConfig(array $raw): IntegrationConfigInterface
    {
        $timeout = isset($raw['timeoutSeconds']) ? (int)$raw['timeoutSeconds'] : null;

        return MindBoxConfig::create(
            MindBoxOperationNameEnum::CREATE_BET,
            null,
            $timeout,
        );
    }

    /**
     * @param DTOInterface $dto
     * @param IntegrationConfigInterface $config
     * @return RequestInterface
     */
    protected function buildRequest(DTOInterface $dto, IntegrationConfigInterface $config): RequestInterface
    {
        return new CreateBetRequest($dto, $config);
    }
}