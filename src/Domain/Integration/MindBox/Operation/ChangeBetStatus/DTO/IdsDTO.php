<?php

namespace Integra\Domain\Integration\MindBox\Operation\ChangeBetStatus\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;

class IdsDTO extends AbstractDTO
{
    public function __construct(
        public readonly int $betId
    )
    {
    }

    /**
     * @inheritDoc
     */
    protected function fields(): array
    {
        return [
            'betId',
        ];
    }
}