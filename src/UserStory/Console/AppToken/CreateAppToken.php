<?php

declare(strict_types=1);

namespace Integra\UserStory\Console\AppToken;

use Integra\Infrastructure\Console\Command;
use Integra\Infrastructure\Generic\Result;
use Integra\Infrastructure\Generic\Result\Failed;
use Integra\Infrastructure\Generic\Result\Successful;
use Integra\Models\Ubet\AppToken;

class CreateAppToken implements Command
{
    private string $application;
    private string $token;

    public function __construct(string $application, string $token)
    {
        $this->application = $application;
        $this->token = $token;
    }

    public function run(): Result
    {
        $appToken = new AppToken();
        $appToken->token = $this->token;
        $appToken->application = $this->application;

        $result = $appToken->save();

        if (!$result) {
            return new Failed($appToken->getErrorSummary(true));
        }

        return new Successful([sprintf('Token for `%s` created.', $this->application)]);
    }

}
