<?php

namespace Integra\Domain\Integration\MindBox\Operation\UpdateBonusStatus\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;

class OrderDTO extends AbstractDTO
{
    public function __construct(
        public readonly IdsDTO $ids
    )
    {
    }

    /**
     * @inheritDoc
     */
    protected function fields(): array
    {
        return [
            'ids',
        ];
    }
}