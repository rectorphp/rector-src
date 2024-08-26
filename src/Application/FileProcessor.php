<?php

declare(strict_types=1);

namespace Rector\Application;

use Nette\Utils\FileSystem;
use Nette\Utils\Strings;
use PhpParser\Node;
use PHPStan\AnalysedCodeException;
use PHPStan\Dependency\DependencyResolver;
use PHPStan\Parser\ParserErrorsException;
use Rector\Caching\Detector\ChangedFilesDetector;
use Rector\Caching\FileDependenciesCache;
use Rector\ChangesReporting\ValueObjectFactory\ErrorFactory;
use Rector\ChangesReporting\ValueObjectFactory\FileDiffFactory;
use Rector\Exception\ShouldNotHappenException;
use Rector\FileSystem\FilePathHelper;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\NodeScopeAndMetadataDecorator;
use Rector\PhpDocParser\NodeTraverser\SimpleCallableNodeTraverser;
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
use Rector\ValueObject\Reporting\FileDiff;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

final readonly class FileProcessor
{
    /**
     * @var string
     * @see https://regex101.com/r/llm7XZ/1
     */
    private const OPEN_TAG_SPACED_REGEX = '#^[ \t]+<\?php#m';

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
        private DependencyResolver $dependencyResolver,
        private SimpleCallableNodeTraverser $simpleCallableNodeTraverser,
        private FileDependenciesCache $fileDependenciesCache,
    ) {
    }

    /**
     * @param array<string,true> $allFiles
     */
    public function processFile(File $file, array $allFiles, Configuration $configuration): FileProcessResult
    {
        // 1. parse files to nodes
        $parsingSystemError = $this->parseFileAndDecorateNodes($file);
        if ($parsingSystemError instanceof SystemError) {
            // we cannot process this file as the parsing and type resolving itself went wrong
            return new FileProcessResult([$parsingSystemError], null);
        }

        $this->cacheFileDependencies($file, $allFiles);

        $fileHasChanged = false;
        $filePath = $file->getFilePath();

        // 2. change nodes with Rectors
        $rectorWithLineChanges = null;

        do {
            $file->changeHasChanged(false);

            $newStmts = $this->rectorNodeTraverser->traverse($file->getNewStmts());

            // apply post rectors
            $postNewStmts = $this->postFileProcessor->traverse($newStmts, $file);

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

            if ($throwable instanceof ParserErrorsException) {
                $throwable = new ParserErrors($throwable);
            }

            return new SystemError($throwable->getMessage(), $relativeFilePath, $throwable->getLine());
        }

        return null;
    }

    /**
     * @param array<string,true> $allFiles
     */
    private function cacheFileDependencies(File $file, array $allFiles): void
    {
        $fileDependencies = [];
        $dependencyResolver = $this->dependencyResolver;
        $this->simpleCallableNodeTraverser->traverseNodesWithCallable(
            $file->getOldStmts(),
            static function (Node $node) use ($dependencyResolver, $allFiles, $file, &$fileDependencies): Node {
                $currentScope = $node->getAttribute(AttributeKey::SCOPE);
                if ($currentScope !== null) {
                    $dependencies = $dependencyResolver->resolveDependencies($node, $currentScope);
                    $fileDependencies = [
                        ...$fileDependencies,
                        ...$dependencies->getFileDependencies($file->getFilePath(), $allFiles),
                    ];
                }

                return $node;
            }
        );
        $fileDependencies = array_values(array_unique($fileDependencies));
        $this->fileDependenciesCache->cacheFileDependencies($file->getFilePath(), $fileDependencies);
    }

    private function printFile(File $file, Configuration $configuration, string $filePath): void
    {
        // only save to string first, no need to print to file when not needed
        $newContent = $this->betterStandardPrinter->printFormatPreserving(
            $file->getNewStmts(),
            $file->getOldStmts(),
            $file->getOldTokens()
        );

        /**
         * When no diff applied, the PostRector may still change the content, that's why printing still needed
         * On printing, the space may be wiped, these below check compare with original file content used to verify
         * that no change actually needed
         */
        if (! $file->getFileDiff() instanceof FileDiff) {
            /**
             * Handle new line or space before <?php or InlineHTML node wiped on print format preserving
             * On very first content level
             */
            $ltrimOriginalFileContent = ltrim($file->getOriginalFileContent());
            if ($ltrimOriginalFileContent === $newContent) {
                return;
            }

            // handle space before <?php
            $ltrimNewContent = Strings::replace($newContent, self::OPEN_TAG_SPACED_REGEX, '<?php');
            $ltrimOriginalFileContent = Strings::replace(
                $ltrimOriginalFileContent,
                self::OPEN_TAG_SPACED_REGEX,
                '<?php'
            );
            if ($ltrimOriginalFileContent === $ltrimNewContent) {
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

        FileSystem::write($filePath, $newContent, null);
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
