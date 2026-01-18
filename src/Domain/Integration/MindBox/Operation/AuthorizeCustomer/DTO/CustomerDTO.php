<?php
declare(strict_types=1);

namespace Integra\Domain\Integration\MindBox\Operation\AuthorizeCustomer\DTO;

use Integra\Domain\Integration\Common\AbstractDTO;
use Integra\Domain\Integration\MindBox\DTO\IdsDTO;

final class CustomerDTO extends AbstractDTO
{
    /**
     * @param IdsDTO $ids
     */
    public function __construct(
        public readonly IdsDTO $ids,
    )
    {
    }


    /**
     * @return string[]
     */
    protected function fields(): array
    {
        return ['ids'];
    }
}
