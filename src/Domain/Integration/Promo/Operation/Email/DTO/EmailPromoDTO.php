<?php

namespace Integra\Domain\Integration\Promo\Operation\Email\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;

class EmailPromoDTO extends AbstractDTO
{
    public function __construct(
        public readonly int $userId,
        public readonly int $typeId
    ) {}

    /**
     * @inheritDoc
     */
    protected function fields(): array
    {
        return [
            'data' => ['user_id'],
            'type_id',
        ];
    }
}