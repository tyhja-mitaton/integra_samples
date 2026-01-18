<?php

namespace Integra\Domain\Integration\MindBox\Operation\UpdateBonusStatus\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;

class IdsDTO extends AbstractDTO
{
    public function __construct(
        public readonly int $bonuseId
    )
    {
    }

    /**
     * @inheritDoc
     */
    protected function fields(): array
    {
        return [
            'bonuseId'
        ];
    }
}