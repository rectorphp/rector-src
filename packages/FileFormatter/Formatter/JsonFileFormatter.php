<?php

declare(strict_types=1);

namespace Rector\FileFormatter\Formatter;

use Ergebnis\Json\Printer\PrinterInterface;
use Rector\Core\ValueObject\Application\File;
use Rector\FileFormatter\Contract\Formatter\FileFormatterInterface;
use Rector\FileFormatter\ValueObject\EditorConfigConfiguration;

/**
 * @see \Rector\Tests\FileFormatter\Formatter\JsonFileFormatter\JsonFileFormatterTest
 */
final class JsonFileFormatter implements FileFormatterInterface
{
    public function __construct(
        private readonly PrinterInterface $jsonPrinter
    ) {
    }

    public function supports(File $file): bool
    {
        $smartFileInfo = $file->getSmartFileInfo();

        return $smartFileInfo->getExtension() === 'json';
    }

    public function format(File $file, EditorConfigConfiguration $editorConfigConfiguration): void
    {
        $newFileContent = $this->jsonPrinter->print(
            $file->getFileContent(),
            $editorConfigConfiguration->getIndent(),
            $editorConfigConfiguration->getNewLine()
        );

        $newFileContent .= $editorConfigConfiguration->getFinalNewline();

        $file->changeFileContent($newFileContent);
    }
}
