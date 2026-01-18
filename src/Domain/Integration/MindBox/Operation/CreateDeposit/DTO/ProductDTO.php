<?php

namespace Integra\Domain\Integration\MindBox\Operation\CreateDeposit\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;

class ProductDTO extends AbstractDTO
{
    public function __construct(
        public readonly IdsDTOProduct          $ids,
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