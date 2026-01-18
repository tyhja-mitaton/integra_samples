<?php

declare(strict_types=1);

namespace Integra\Infrastructure\Generic\Result;

use Integra\Infrastructure\Generic\Result;

class Merged implements Result
{
    private array $results;
    private ?Result $result;

    public function __construct(Result ...$results)
    {
        $this->results = $results;
        $this->result = null;
    }

    public function isSuccessful(): bool
    {
        return $this->cachedResult()->isSuccessful();
    }

    public function value(): array
    {
        return $this->cachedResult()->value();
    }

    public function error(): array
    {
        return $this->cachedResult()->error();
    }

    private function cachedResult(): Result
    {
        if (is_null($this->result)) {
            $this->result = $this->mergeResults();
        }

        return $this->result;
    }

    private function mergeResults(): Result
    {
        $values = [];
        $errors = [];

        foreach ($this->results as $result) {
            if ($result->isSuccessful()) {
	            $values =
		            array_merge_recursive(
			            $values,
			            $result->value()
		            );
            } else {
	            $errors =
		            array_merge_recursive(
			            $errors,
			            $result->error()
		            );
            }
        }

        return empty($errors) ? new Successful($values) : new Failed($errors);
    }
}
