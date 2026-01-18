<?php

namespace Integra\Domain\Integration\MindBox\Operation\ChangeBet\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;

class LineDTO extends AbstractDTO
{

    public function __construct(
        public readonly LineCustomFieldsDTO $customFields,
        public readonly string $quantity,
        public readonly LineProductDTO $product,
        public ?string $basePricePerItem = null,
        public ?int $status = null,
        public ?string $discountedPriceOfLine = null
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
            'basePricePerItem',
            'status',
            'discountedPriceOfLine'
        ];
    }
}