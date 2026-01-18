<?php

namespace Integra\Domain\Integration\MindBox\Operation\UpdateDepositStatus\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;

class IdsDTO extends AbstractDTO
{

    public function __construct(
        public readonly string $depositId,
    )
    {
    }

    /**
     * @inheritDoc
     */
    protected function fields(): array
    {
        return ['depositId'];
    }
}