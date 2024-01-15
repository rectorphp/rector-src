<?php

declare(strict_types=1);

namespace Rector\Application;

use PHPStan\AnalysedCodeException;
use Rector\Caching\Detector\ChangedFilesDetector;
use Rector\ChangesReporting\ValueObjectFactory\ErrorFactory;
use Rector\ChangesReporting\ValueObjectFactory\FileDiffFactory;
use Rector\Exception\ShouldNotHappenException;
use Rector\FileSystem\FilePathHelper;
use Rector\NodeTypeResolver\NodeScopeAndMetadataDecorator;
use Rector\PhpParser\Node\CustomNode\FileWithoutNamespace;
use Rector\PhpParser\NodeTraverser\RectorNodeTraverser;
use Rector\PhpParser\Parser\RectorParser;
use Rector\PhpParser\Printer\FormatPerservingPrinter;
use Rector\PostRector\Application\PostFileProcessor;
use Rector\Testing\PHPUnit\StaticPHPUnitEnvironment;
use Rector\ValueObject\Application\File;
use Rector\ValueObject\Configuration;
use Rector\ValueObject\Error\SystemError;
use Rector\ValueObject\FileProcessResult;
use Rector\ValueObject\Reporting\FileDiff;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

final readonly class FileProcessor
{
    public function __construct(
        private FormatPerservingPrinter $formatPerservingPrinter,
        private RectorNodeTraverser $rectorNodeTraverser,
        private SymfonyStyle $symfonyStyle,
        private FileDiffFactory $fileDiffFactory,
        private ChangedFilesDetector $changedFilesDetector,
        private ErrorFactory $errorFactory,
        private FilePathHelper $filePathHelper,
        private PostFileProcessor $postFileProcessor,
        private RectorParser $rectorParser,
        private NodeScopeAndMetadataDecorator $nodeScopeAndMetadataDecorator,
    ) {
    }

    public function processFile(File $file, Configuration $configuration): FileProcessResult
    {
        // 1. parse files to nodes
        $parsingSystemError = $this->parseFileAndDecorateNodes($file);
        if ($parsingSystemError instanceof SystemError) {
            // we cannot process this file as the parsing and type resolving itself went wrong
            return new FileProcessResult([$parsingSystemError], null);
        }

        $fileHasChanged = false;
        $filePath = $file->getFilePath();

        // 2. change nodes with Rectors
        $rectorWithLineChanges = null;

        do {
            $file->changeHasChanged(false);

            $newStmts = $this->rectorNodeTraverser->traverse($file->getNewStmts());

            // apply post rectors
            $postNewStmts = $this->postFileProcessor->traverse($newStmts, $filePath);

            // this is needed for new tokens added in "afterTraverse()"
            $file->changeNewStmts($postNewStmts);

            // 3. print to file or string
            // important to detect if file has changed
            $this->printFile($file, $configuration, $filePath);

            $fileHasChangedInCurrentPass = $file->hasChanged();

            if ($fileHasChangedInCurrentPass) {
                $file->setFileDiff($this->fileDiffFactory->createTempFileDiff($file));
                $rectorWithLineChanges = $file->getRectorWithLineChanges();

                $fileHasChanged = true;
            }
        } while ($fileHasChangedInCurrentPass);

        // 5. add as cacheable if not changed at all
        if (! $fileHasChanged) {
            $this->changedFilesDetector->addCachableFile($filePath);
        }

        if ($configuration->shouldShowDiffs() && $rectorWithLineChanges !== null) {
            $currentFileDiff = $this->fileDiffFactory->createFileDiffWithLineChanges(
                $file,
                $file->getOriginalFileContent(),
                $file->getFileContent(),
                $rectorWithLineChanges
            );
            $file->setFileDiff($currentFileDiff);
        }

        return new FileProcessResult([], $file->getFileDiff());
    }

    private function parseFileAndDecorateNodes(File $file): ?SystemError
    {
        try {
            $this->parseFileNodes($file);
        } catch (ShouldNotHappenException $shouldNotHappenException) {
            throw $shouldNotHappenException;
        } catch (AnalysedCodeException $analysedCodeException) {
            // inform about missing classes in tests
            if (StaticPHPUnitEnvironment::isPHPUnitRun()) {
                throw $analysedCodeException;
            }

            return $this->errorFactory->createAutoloadError($analysedCodeException, $file->getFilePath());
        } catch (Throwable $throwable) {
            if ($this->symfonyStyle->isVerbose() || StaticPHPUnitEnvironment::isPHPUnitRun()) {
                throw $throwable;
            }

            $relativeFilePath = $this->filePathHelper->relativePath($file->getFilePath());

            return new SystemError($throwable->getMessage(), $relativeFilePath, $throwable->getLine());
        }

        return null;
    }

    private function printFile(File $file, Configuration $configuration, string $filePath): void
    {
        // only save to string first, no need to print to file when not needed
        $newContent = $this->formatPerservingPrinter->printParsedStmstAndTokensToString($file);

        /**
         * When no diff applied, the PostRector may still change the content, that's why printing still needed
         * On printing, the space may be wiped, these below check compare with original file content used to verify
         * that no change actually needed
         */
        if (! $file->getFileDiff() instanceof FileDiff && current(
            $file->getNewStmts()
        ) instanceof FileWithoutNamespace) {
            /**
             * Handle new line or space before <?php or InlineHTML node wiped on print format preserving
             * On very first content level
             */
            $originalFileContent = $file->getOriginalFileContent();
            $ltrimOriginalFileContent = ltrim($originalFileContent);

            if ($ltrimOriginalFileContent === $newContent) {
                return;
            }
        }

        // change file content early to make $file->hasChanged() based on new content
        $file->changeFileContent($newContent);
        if ($configuration->isDryRun()) {
            return;
        }

        if (! $file->hasChanged()) {
            return;
        }

        $this->formatPerservingPrinter->dumpFile($filePath, $newContent);
    }

    private function parseFileNodes(File $file): void
    {
        // store tokens by original file content, so we don't have to print them right now
        $stmtsAndTokens = $this->rectorParser->parseFileContentToStmtsAndTokens($file->getOriginalFileContent());

        $oldStmts = $stmtsAndTokens->getStmts();
        $oldTokens = $stmtsAndTokens->getTokens();

        $newStmts = $this->nodeScopeAndMetadataDecorator->decorateNodesFromFile($file->getFilePath(), $oldStmts);
        $file->hydrateStmtsAndTokens($newStmts, $oldStmts, $oldTokens);
    }
}
