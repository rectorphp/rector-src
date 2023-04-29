<?php

declare(strict_types=1);

namespace Rector\Core\Application\FileDecorator;

use Rector\ChangesReporting\ValueObjectFactory\FileDiffFactory;
use Rector\Core\ValueObject\Application\File;
use Rector\Core\ValueObject\Configuration;

final class FileDiffFileDecorator
{
    public function __construct(
        private readonly FileDiffFactory $fileDiffFactory
    ) {
    }

    /**
     * @param File[] $files
     */
    public function decorate(array $files, Configuration $configuration): void
    {
        foreach ($files as $file) {
            if (! $file->hasChanged()) {
                continue;
            }

            $fileDiff = $this->fileDiffFactory->createFileDiff(
                $file,
                $file->getOriginalFileContent(),
                $file->getFileContent(),
                $configuration
            );

            $file->setFileDiff($fileDiff);
        }
    }
}
