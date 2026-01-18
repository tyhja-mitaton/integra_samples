<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\MindBox\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;

final class SubscriptionDTO extends AbstractDTO
{
    public function __construct(
        public readonly string $brand,
        public readonly string $pointOfContact,
        public readonly bool   $isSubscribed,
    )
    {
    }

    /**
     * @return string[]
     */
    protected function fields(): array
    {
        return [
            'brand',
            'pointOfContact',
            'isSubscribed',
        ];
    }
}
