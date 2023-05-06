<?php

declare(strict_types=1);

namespace Rector\Core\Contract\Processor;

use Rector\Core\Exception\ParsingException;
use Rector\Core\ValueObject\Application\File;
use Rector\Core\ValueObject\Configuration;
use Rector\Core\ValueObject\Reporting\FileDiff;

interface FileProcessorInterface
{
    public function supports(File $file, Configuration $configuration): bool;

    /**
     * @throws ParsingException
     */
    public function process(File $file, Configuration $configuration): ?FileDiff;

    /**
     * @return string[]
     */
    public function getSupportedFileExtensions(): array;
}
