<?php

namespace Integra\Domain\Integration\MindBox\Operation\ChangeBetStatus\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;

class OrderDTO extends AbstractDTO
{
    public function __construct(
        public readonly IdsDTO $ids,
        public readonly CustomFieldsDTO   $customFields
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
            'customFields',
        ];
    }
}