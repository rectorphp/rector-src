<?php

declare(strict_types=1);

namespace Rector\Core\PhpParser\Printer;

use PhpParser\Node;
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
     * @api tests
     *
     * @param Node[] $newStmts
     * @param Node[] $oldStmts
     * @param Node[] $oldTokens
     */
    public function printToFile(string $filePath, array $newStmts, array $oldStmts, array $oldTokens): string
    {
        $newContent = $this->betterStandardPrinter->printFormatPreserving($newStmts, $oldStmts, $oldTokens);

        $this->dumpFile($filePath, $newContent);

        return $newContent;
    }

    public function printParsedStmstAndTokensToString(File $file): string
    {
        return $this->betterStandardPrinter->printFormatPreserving(
            $file->getNewStmts(),
            $file->getOldStmts(),
            $file->getOldTokens()
        );
    }

    public function dumpFile(string $filePath, string $newContent): void
    {
        $this->filesystem->dumpFile($filePath, $newContent);

        // @todo how to keep origianl access rights without the SplFileInfo
        // $this->filesystem->chmod($filePath, $fileInfo->getPerms());
    }
}
