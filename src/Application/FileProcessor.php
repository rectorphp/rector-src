<?php

declare(strict_types=1);

namespace Rector\Core\Application;

use PHPStan\AnalysedCodeException;
use Rector\Caching\Detector\ChangedFilesDetector;
use Rector\ChangesReporting\ValueObjectFactory\ErrorFactory;
use Rector\ChangesReporting\ValueObjectFactory\FileDiffFactory;
use Rector\Core\Application\Collector\CollectorProcessor;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\FileSystem\FilePathHelper;
use Rector\Core\PhpParser\Node\CustomNode\FileWithoutNamespace;
use Rector\Core\PhpParser\NodeTraverser\RectorNodeTraverser;
use Rector\Core\PhpParser\Parser\RectorParser;
use Rector\Core\PhpParser\Printer\FormatPerservingPrinter;
use Rector\Core\ValueObject\Application\File;
use Rector\Core\ValueObject\Configuration;
use Rector\Core\ValueObject\Error\SystemError;
use Rector\Core\ValueObject\FileProcessResult;
use Rector\Core\ValueObject\Reporting\FileDiff;
use Rector\NodeTypeResolver\NodeScopeAndMetadataDecorator;
use Rector\PostRector\Application\PostFileProcessor;
use Rector\Testing\PHPUnit\StaticPHPUnitEnvironment;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

final class FileProcessor
{
    public function __construct(
        private readonly FormatPerservingPrinter $formatPerservingPrinter,
        private readonly RectorNodeTraverser $rectorNodeTraverser,
        private readonly SymfonyStyle $symfonyStyle,
        private readonly FileDiffFactory $fileDiffFactory,
        private readonly ChangedFilesDetector $changedFilesDetector,
        private readonly ErrorFactory $errorFactory,
        private readonly FilePathHelper $filePathHelper,
        private readonly CollectorProcessor $collectorProcessor,
        private readonly PostFileProcessor $postFileProcessor,
        private readonly RectorParser $rectorParser,
        private readonly NodeScopeAndMetadataDecorator $nodeScopeAndMetadataDecorator,
    ) {
    }

    public function processFile(File $file, Configuration $configuration): FileProcessResult
    {
        if ($configuration->isSecondRun() && $configuration->isCollectors()) {
            // 2nd run
            $this->rectorNodeTraverser->prepareCollectorRectorsRun($configuration);
        }

        // 1. parse files to nodes
        $parsingSystemError = $this->parseFileAndDecorateNodes($file);
        if ($parsingSystemError instanceof SystemError) {
            // we cannot process this file as the parsing and type resolving itself went wrong
            return new FileProcessResult([$parsingSystemError], null, []);
        }

        $fileHasChanged = false;

        // 2. change nodes with Rectors
        $rectorWithLineChanges = null;

        do {
            $file->changeHasChanged(false);

            $newStmts = $this->rectorNodeTraverser->traverse($file->getNewStmts());

            // collect data
            $fileCollectedData = $configuration->isCollectors() ? $this->collectorProcessor->process($newStmts) : [];

            // apply post rectors
            $postNewStmts = $this->postFileProcessor->traverse($newStmts, $file->getFilePath());

            // this is needed for new tokens added in "afterTraverse()"
            $file->changeNewStmts($postNewStmts);

            // 3. print to file or string
            // important to detect if file has changed
            $this->printFile($file, $configuration);

            $fileHasChangedInCurrentPass = $file->hasChanged();

            if ($fileHasChangedInCurrentPass) {
                $file->setFileDiff($this->fileDiffFactory->createTempFileDiff($file));
                $rectorWithLineChanges = $file->getRectorWithLineChanges();

                $fileHasChanged = true;
            }
        } while ($fileHasChangedInCurrentPass);

        // 5. add as cacheable if not changed at all
        if (! $fileHasChanged) {
            $this->changedFilesDetector->addCachableFile($file->getFilePath());
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

        return new FileProcessResult([], $file->getFileDiff(), $fileCollectedData);
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

    private function printFile(File $file, Configuration $configuration): void
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

        $this->formatPerservingPrinter->dumpFile($file->getFilePath(), $newContent);
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
