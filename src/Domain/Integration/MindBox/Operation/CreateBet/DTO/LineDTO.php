<?php

namespace Integra\Domain\Integration\MindBox\Operation\CreateBet\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;

class LineDTO extends AbstractDTO
{
    public function __construct(
        public readonly LineCustomFieldsDTO $customFields,
        public readonly string $quantity,
        public readonly LineProductDTO $product,
        public ?string $basePricePerItem = null
    )
    {
    }

    /**
     * @inheritDoc
     */
    protected function fields(): array
    {
        return [
            'customFields',
            'product',
            'quantity',
            'basePricePerItem'
        ];
    }
}