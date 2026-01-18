<?php

namespace Integra\Domain\Integration\MindBox\Operation\ChangeBet\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;

class LineCustomFieldsDTO extends AbstractDTO
{
    public function __construct(
        public ?string $championship = null,
        public ?string $lineLive = null,
        public ?string $sport = null,
        public ?string $game = null,
    )
    {
    }

    /**
     * @inheritDoc
     */
    protected function fields(): array
    {
        return [
            'championship',
            'lineLive',
            'sport',
            'game',
        ];
    }
}