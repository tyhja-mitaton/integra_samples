<?php

namespace Integra\Domain\Integration\MindBox\Operation\ChangeBet\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;

class IdsDTOOrder extends AbstractDTO
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