<?php

declare(strict_types=1);

namespace Rector\Core\PhpParser\Printer;

use PhpParser\Node;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Namespace_;
use Rector\Core\PhpParser\Node\CustomNode\FileWithoutNamespace;
use Rector\Core\ValueObject\Application\File;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @see \Rector\Core\Tests\PhpParser\Printer\FormatPerservingPrinterTest
 */
final class FormatPerservingPrinter
{
    public function __construct(
        private readonly BetterStandardPrinter $betterStandardPrinter,
        private readonly Filesystem $filesystem
    ) {
    }

    /**
     * @param Node[] $newStmts
     * @param Node[] $oldStmts
     * @param Node[] $oldTokens
     */
    public function printToFile(string $filePath, array $newStmts, array $oldStmts, array $oldTokens): string
    {
        $newContent = $this->betterStandardPrinter->printFormatPreserving($newStmts, $oldStmts, $oldTokens);

        $this->filesystem->dumpFile($filePath, $newContent);

        // @todo how to keep origianl access rights without the SplFileInfo
        // $this->filesystem->chmod($filePath, $fileInfo->getPerms());

        return $newContent;
    }

    public function printParsedStmstAndTokensToString(File $file): string
    {
        $newStmts = $this->resolveNewStmts($file);

        return $this->betterStandardPrinter->printFormatPreserving(
            $newStmts,
            $file->getOldStmts(),
            $file->getOldTokens()
        );
    }

    public function printParsedStmstAndTokens(File $file): string
    {
        $newStmts = $this->resolveNewStmts($file);

        return $this->printToFile($file->getFilePath(), $newStmts, $file->getOldStmts(), $file->getOldTokens());
    }

    /**
     * @return Stmt[]|mixed[]
     */
    private function resolveNewStmts(File $file): array
    {
        $newStmts = $file->getNewStmts();

        if (count($newStmts) !== 1) {
            return $newStmts;
        }

        /** @var Namespace_|FileWithoutNamespace $onlyStmt */
        $onlyStmt = $newStmts[0];
        if (! $onlyStmt instanceof FileWithoutNamespace) {
            return $newStmts;
        }

        return $onlyStmt->stmts;
    }
}
