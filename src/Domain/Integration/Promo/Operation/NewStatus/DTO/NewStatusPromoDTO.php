<?php

namespace Integra\Domain\Integration\Promo\Operation\NewStatus\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;

class NewStatusPromoDTO extends AbstractDTO
{
    public function __construct(
        public readonly int $userId,
        public readonly int $statusId,
        public readonly int $typeId
    ) {}

    /**
     * @inheritDoc
     */
    protected function fields(): array
    {
        return [
            'data' => ['user_id', 'status_id'],
            'type_id',
        ];
    }
}