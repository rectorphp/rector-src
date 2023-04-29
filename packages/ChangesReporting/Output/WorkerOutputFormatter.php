<?php

declare(strict_types=1);

namespace Rector\ChangesReporting\Output;

use Nette\Utils\Json;
use Rector\ChangesReporting\Annotation\RectorsChangelogResolver;
use Rector\ChangesReporting\Contract\Output\OutputFormatterInterface;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\ValueObject\Configuration;
use Rector\Core\ValueObject\Error\SystemError;
use Rector\Core\ValueObject\ProcessResult;
use Rector\Parallel\ValueObject\Bridge;

final class WorkerOutputFormatter implements OutputFormatterInterface
{
    /**
     * @var string
     */
    public const NAME = 'worker';

    public function __construct(
        private readonly JsonOutputFormatter $jsonOutputFormatter
    ) {
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function report(ProcessResult $processResult, Configuration $configuration): void
    {
        echo $this->jsonOutputFormatter->report($processResult, $configuration);
    }
}
