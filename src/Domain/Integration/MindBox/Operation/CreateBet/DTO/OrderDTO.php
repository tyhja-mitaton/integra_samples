<?php

namespace Integra\Domain\Integration\MindBox\Operation\CreateBet\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;

class OrderDTO extends AbstractDTO
{
    public function __construct(
        public readonly IdsDTOOrder          $ids,
        public readonly CustomFieldsDTO      $customFields,
        public readonly array                $lines
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
            'lines',
        ];
    }
}