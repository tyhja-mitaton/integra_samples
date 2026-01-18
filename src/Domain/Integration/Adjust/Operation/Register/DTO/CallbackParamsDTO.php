<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\Adjust\Operation\Register\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;

final class CallbackParamsDTO extends AbstractDTO
{
    public function __construct(
        public readonly string $userId,
        public readonly string $status,
        public readonly string $goal,
        public readonly string $datetime,
        public readonly string $partnerId,
        public readonly string $offerId,
    )
    {
    }

    protected function fields(): array
    {
        return [
            'userId' => 'custom1',
            'status',
            'goal',
            'datetime',
            'partnerId' => 'partner_id',
            'offerId' => 'offer_id',
        ];
    }
}