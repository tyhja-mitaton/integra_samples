<?php

namespace Integra\Domain\Integration\MindBox\Operation\CreateBonus\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;

class LineDTO extends AbstractDTO
{
    public function __construct(
        public readonly float $basePricePerItem,
        public readonly string $quantity,
        public readonly ProductDTO $product
    )
    {
    }

    /**
     * @inheritDoc
     */
    protected function fields(): array
    {
        return [
            'basePricePerItem',
            'quantity',
            'product',
        ];
    }
}