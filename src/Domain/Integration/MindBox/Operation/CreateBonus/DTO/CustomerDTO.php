<?php

namespace Integra\Domain\Integration\MindBox\Operation\CreateBonus\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;
use Integra\Domain\Integration\MindBox\DTO\IdsDTO;

class CustomerDTO extends AbstractDTO
{
    public function __construct(
        public readonly IdsDTO          $ids,
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