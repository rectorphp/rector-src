<?php

declare(strict_types=1);

namespace Rector\Config;

use Rector\Core\Configuration\Option;

final class ParallelConfig
{
    public function __construct(
        private readonly RectorConfig $rectorConfig
    ) {
    }

    public function maxNumberOfProcess(int $numberOfProcess): self
    {
        $parameters = $this->rectorConfig->parameters();
        $parameters->set(Option::PARALLEL_MAX_NUMBER_OF_PROCESSES, $numberOfProcess);

        return $this;
    }

    public function jobTimeout(int $seconds): self
    {
        $parameters = $this->rectorConfig->parameters();
        $parameters->set(Option::PARALLEL_JOB_TIMEOUT_IN_SECONDS, $seconds);

        return $this;
    }

    public function jobSize(int $size): self
    {
        $parameters = $this->rectorConfig->parameters();
        $parameters->set(Option::PARALLEL_JOB_SIZE, $size);

        return $this;
    }
}
