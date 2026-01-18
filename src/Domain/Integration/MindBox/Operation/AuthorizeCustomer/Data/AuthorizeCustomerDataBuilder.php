<?php

declare(strict_types=1);

namespace Integra\Domain\Integration\MindBox\Operation\AuthorizeCustomer\Data;

use DateTime;
use Exception;
use Integra\Infrastructure\Datetime\UTC;
use Integra\Models\Ubet\UsersAuthHistory;
use Integra\Infrastructure\Datetime\Asia;
use Integra\Domain\Integration\MindBox\DTO\IdsDTO;
use Integra\Domain\Integration\MindBox\Operation\AuthorizeCustomer\DTO\CustomerDTO;
use Integra\Domain\Integration\MindBox\Operation\AuthorizeCustomer\DTO\AuthorizeCustomerDTO;

final class AuthorizeCustomerDataBuilder
{
    /**
     * @param UsersAuthHistory $userAuthHistory
     * @param string|null $userAgent
     * @return AuthorizeCustomerDTO
     * @throws Exception
     */
    public function build(UsersAuthHistory $userAuthHistory, ?string $userAgent = null): AuthorizeCustomerDTO
    {
        $authorizationDateTime = new DateTime($userAuthHistory->dttm, new Asia());
        $authorizationDateTimeUtc = $authorizationDateTime->setTimezone(new UTC());
        $executionDateTimeUtc = $authorizationDateTimeUtc->format('Y-m-d H:i:s');

        $deviceUUID = null;
        //todo можно вынести
        if (!empty($auth->mindbox_uuid) && preg_match(
                '/^[0-9A-Za-z]{8}-[0-9A-Za-z]{4}-[0-9A-Za-z]{4}-[0-9A-Za-z]{4}-[0-9A-Za-z]{12}$/',
                $auth->mindbox_uuid
            )) {
            $deviceUUID = $auth->mindbox_uuid;
        }

        $ids = new IdsDTO(login: (string)$userAuthHistory->user_id);

        return new AuthorizeCustomerDTO(
            customer: new CustomerDTO($ids),
            executionDateTimeUtc: $executionDateTimeUtc,
            deviceUUID: $deviceUUID,
            customerIp: $userAuthHistory->ip,
            userAgent: $userAgent
        );
    }
}