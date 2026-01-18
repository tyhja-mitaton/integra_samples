<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\Promo\Operation\Register\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;


final class RegisterPromoDTO extends AbstractDTO
{
    public function __construct(
        public readonly int $userId,
        public readonly int $typeId
    ) {}

    protected function fields(): array
    {
        return [
            'data' => ['user_id'],
            'type_id',
        ];
    }
}