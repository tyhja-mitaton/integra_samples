<?php

namespace Integra\Domain\Integration\MindBox\Operation\CreateDeposit\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;

class IdsDTOProduct extends AbstractDTO
{
    public function __construct(
        public readonly string $ubet,
    )
    {
    }

    /**
     * @inheritDoc
     */
    protected function fields(): array
    {
        return [
            'ubet',
        ];
    }
}