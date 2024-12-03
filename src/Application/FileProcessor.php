<?php

declare(strict_types=1);

namespace Rector\Application;

use Nette\Utils\FileSystem;
use PHPStan\AnalysedCodeException;
use PHPStan\Parser\ParserErrorsException;
use Rector\Caching\Detector\ChangedFilesDetector;
use Rector\ChangesReporting\ValueObjectFactory\ErrorFactory;
use Rector\ChangesReporting\ValueObjectFactory\FileDiffFactory;
use Rector\Exception\ShouldNotHappenException;
use Rector\FileSystem\FilePathHelper;
use Rector\NodeTypeResolver\NodeScopeAndMetadataDecorator;
use Rector\PhpParser\NodeTraverser\RectorNodeTraverser;
use Rector\PhpParser\Parser\ParserErrors;
use Rector\PhpParser\Parser\RectorParser;
use Rector\PhpParser\Printer\BetterStandardPrinter;
use Rector\PostRector\Application\PostFileProcessor;
use Rector\Testing\PHPUnit\StaticPHPUnitEnvironment;
use Rector\ValueObject\Application\File;
use Rector\ValueObject\Configuration;
use Rector\ValueObject\Error\SystemError;
use Rector\ValueObject\FileProcessResult;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

final readonly class FileProcessor
{
    public function __construct(
        private BetterStandardPrinter $betterStandardPrinter,
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
        // parse files to nodes
        $parsingSystemError = $this->parseFileAndDecorateNodes($file);
        if ($parsingSystemError instanceof SystemError) {
            // we cannot process this file as the parsing and type resolving itself went wrong
            return new FileProcessResult([$parsingSystemError], null);
        }

        $fileHasChanged = false;
        $filePath = $file->getFilePath();

        do {
            $file->changeHasChanged(false);

            $newStmts = $this->rectorNodeTraverser->traverse($file->getNewStmts());

            // apply post rectors
            $postNewStmts = $this->postFileProcessor->traverse($newStmts, $file);

            // this is needed for new tokens added in "afterTraverse()"
            $file->changeNewStmts($postNewStmts);

            // print to file or string
            // important to detect if file has changed
            $this->printFile($file, $configuration, $filePath);

            // no change on current iteration, stop!
            if (! $file->hasChanged()) {
                break;
            }

            // mark flag changed to be used after loop
            $fileHasChanged = true;
        } while (true);

        // add as cacheable if not changed at all
        if (! $fileHasChanged) {
            $this->changedFilesDetector->addCachableFile($filePath);
        } elseif ($configuration->shouldShowDiffs()) {
            $file->setFileDiff(
                $this->fileDiffFactory->createFileDiffWithLineChanges(
                    $file,
                    $file->getOriginalFileContent(),
                    $file->getFileContent(),
                    $file->getRectorWithLineChanges()
                )
            );
        }

        return new FileProcessResult([], $file->getFileDiff());
    }

    private function parseFileAndDecorateNodes(File $file): ?SystemError
    {
        try {
            try {
                $this->parseFileNodes($file);
            } catch (ParserErrorsException) {
                $this->parseFileNodes($file, false);
            }
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

            if ($throwable instanceof ParserErrorsException) {
                $throwable = new ParserErrors($throwable);
            }

            return new SystemError($throwable->getMessage(), $relativeFilePath, $throwable->getLine());
        }

        return null;
    }

    private function printFile(File $file, Configuration $configuration, string $filePath): void
    {
        // only save to string first, no need to print to file when not needed
        $newContent = $this->betterStandardPrinter->printFormatPreserving(
            $file->getNewStmts(),
            $file->getOldStmts(),
            $file->getOldTokens()
        );

        // change file content early to make $file->hasChanged() based on new content
        $file->changeFileContent($newContent);
        if ($configuration->isDryRun()) {
            return;
        }

        if (! $file->hasChanged()) {
            return;
        }

        FileSystem::write($filePath, $newContent, null);
    }

    private function parseFileNodes(File $file, bool $forNewestSupportedVersion = true): void
    {
        // store tokens by original file content, so we don't have to print them right now
        $stmtsAndTokens = $this->rectorParser->parseFileContentToStmtsAndTokens(
            $file->getOriginalFileContent(),
            $forNewestSupportedVersion
        );

        $oldStmts = $stmtsAndTokens->getStmts();
        $oldTokens = $stmtsAndTokens->getTokens();

        $newStmts = $this->nodeScopeAndMetadataDecorator->decorateNodesFromFile($file->getFilePath(), $oldStmts);
        $file->hydrateStmtsAndTokens($newStmts, $oldStmts, $oldTokens);
    }
}
