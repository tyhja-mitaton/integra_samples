<?php

declare(strict_types=1);

namespace Integra\UserStory\ExternalApplication\Affise;

/**
 * Trait ResponseErrorTrait
 *
 * @package Integra\UserStory\ExternalApplication\Affise
 */
trait ResponseErrorTrait
{
    /**
     * @param $response
     *
     * @return string|null
     */
    public function getErrorText($response): ?string
    {
        $data = json_decode($response, true);

        return $data['error']['text'] ?? null;
    }
}
