<?php

declare(strict_types=1);

namespace Rector\Core\Contract\Processor;

use Rector\Core\ValueObject\Application\File;
use Rector\Core\ValueObject\Configuration;

interface FileProcessorInterface
{
    public function supports(File $file, Configuration $configuration): bool;

    /**
     * @param File[] $files
     */
    public function process(array $files, Configuration $configuration): void;
}
