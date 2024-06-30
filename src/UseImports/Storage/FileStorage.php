<?php

declare(strict_types=1);

namespace Rector\UseImports\Storage;

use PhpParser\Node\Stmt;
use Rector\ValueObject\Application\File;
use Webmozart\Assert\Assert;

final class FileStorage
{
    /**
     * @var array<string, File>
     */
    private array $files = [];

    public function addFile(string $filePath, File $file): void
    {
        $this->files[$filePath] = $file;
    }

    /**
     * @return array<Stmt>
     */
    public function getStmtsByFile(string $filePath): array
    {
        if (! isset($this->files[$filePath])) {
            return [];
        }

        Assert::keyExists($this->files, $filePath);

        /** @var File $currentFile */
        $currentFile = $this->files[$filePath];
        return $currentFile->getNewStmts();
    }
}
