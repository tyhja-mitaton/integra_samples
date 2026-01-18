<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\Alanbase\Operation\RegisterGoal\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;

final class RegisterGoalDTO extends AbstractDTO
{
    public function __construct(
        public readonly string $clickId,
        public readonly string $goal,
        public readonly string $status,
        public readonly int    $userId,
        public readonly int    $datetime,
    ) {}

    protected function fields(): array
    {
        return [
            'clickId'  => 'click_id',
            'goal',
            'status',
            'userId'   => 'custom1',
            'datetime'
        ];
    }
}