<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\MindBox\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;

final class IdsDTO extends AbstractDTO
{
    public function __construct(
        public readonly string $login,
    )
    {
    }

    /**
     * @return string[]
     */
    protected function fields(): array
    {
        return ['login'];
    }
}
