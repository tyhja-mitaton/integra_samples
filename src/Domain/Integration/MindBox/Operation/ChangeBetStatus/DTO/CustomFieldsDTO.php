<?php

namespace Integra\Domain\Integration\MindBox\Operation\ChangeBetStatus\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;

class CustomFieldsDTO extends AbstractDTO
{
    public function __construct(
        public ?float $prizeAmount = null
    )
    {
    }

    /**
     * @inheritDoc
     */
    protected function fields(): array
    {
        return [
            'prizeAmount'
        ];
    }
}