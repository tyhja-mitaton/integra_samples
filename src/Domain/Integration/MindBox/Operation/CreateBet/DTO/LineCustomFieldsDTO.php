<?php

namespace Integra\Domain\Integration\MindBox\Operation\CreateBet\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;

class LineCustomFieldsDTO extends AbstractDTO
{
    public function __construct(
        public ?string $result = null,
        public ?string $matchdateandtime = null,
        public ?string $championship = null,
        public ?string $lineLive = null,
        public ?int $sport = null,
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
            'result',
            'matchdateandtime',
            'championship',
            'lineLive',
            'sport',
            'game',
        ];
    }
}