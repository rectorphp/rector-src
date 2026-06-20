<?php

declare(strict_types=1);

namespace Rector\ChangesReporting\Output;

use Rector\ChangesReporting\Contract\Output\OutputFormatterInterface;
use Rector\ChangesReporting\Output\Factory\JsonOutputFactory;
use Rector\Reporting\UnusedSkipResolver;
use Rector\ValueObject\Configuration;
use Rector\ValueObject\ProcessResult;

final readonly class JsonOutputFormatter implements OutputFormatterInterface
{
    public const string NAME = 'json';

    public function __construct(
        private UnusedSkipResolver $unusedSkipResolver
    ) {
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function report(ProcessResult $processResult, Configuration $configuration): void
    {
        // console output is silenced in json mode, so unused skips are surfaced in the payload
        $unusedSkips = $this->unusedSkipResolver->resolve($processResult);

        echo JsonOutputFactory::create($processResult, $configuration, $unusedSkips) . PHP_EOL;
    }
}
