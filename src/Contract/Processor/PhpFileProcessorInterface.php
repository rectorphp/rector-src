<?php

declare(strict_types=1);

namespace Rector\Core\Contract\Processor;

use Rector\Core\ValueObject\Application\File;

interface PhpFileProcessorInterface
{
    public function __invoke(File $file): void;

    public function getPhase(): string;

    /**
     * Higher values are executed first
     */
    public function getPriority(): int;
}
