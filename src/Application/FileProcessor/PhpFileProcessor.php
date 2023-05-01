<?php

declare(strict_types=1);

namespace Rector\Core\Application\FileProcessor;

use Nette\Utils\Strings;
use PHPStan\AnalysedCodeException;
use Rector\Caching\Detector\ChangedFilesDetector;
use Rector\ChangesReporting\ValueObjectFactory\ErrorFactory;
use Rector\ChangesReporting\ValueObjectFactory\FileDiffFactory;
use Rector\Core\Application\FileProcessor;
use Rector\Core\Application\FileSystem\RemovedAndAddedFilesCollector;
use Rector\Core\Contract\Console\OutputStyleInterface;
use Rector\Core\Contract\Processor\FileProcessorInterface;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\Exception\SystemErrorException;
use Rector\Core\FileSystem\FilePathHelper;
use Rector\Core\PhpParser\Printer\FormatPerservingPrinter;
use Rector\Core\Provider\CurrentFileProvider;
use Rector\Core\ValueObject\Application\File;
use Rector\Core\ValueObject\Configuration;
use Rector\Core\ValueObject\Error\SystemError;
use Rector\Core\ValueObject\Reporting\FileDiff;
use Rector\Parallel\ValueObject\Bridge;
use Rector\PostRector\Application\PostFileProcessor;
use Rector\Testing\PHPUnit\StaticPHPUnitEnvironment;
use Throwable;

final class PhpFileProcessor implements FileProcessorInterface
{
    /**
     * @var string
     * @see https://regex101.com/r/xP2MGa/1
     */
    private const OPEN_TAG_SPACED_REGEX = '#^(?<open_tag_spaced>[^\S\r\n]+\<\?php)#m';

    public function __construct(
        private readonly FormatPerservingPrinter $formatPerservingPrinter,
        private readonly FileProcessor $fileProcessor,
        private readonly RemovedAndAddedFilesCollector $removedAndAddedFilesCollector,
        private readonly OutputStyleInterface $rectorOutputStyle,
        private readonly FileDiffFactory $fileDiffFactory,
        private readonly ChangedFilesDetector $changedFilesDetector,
        private readonly CurrentFileProvider $currentFileProvider,
        private readonly PostFileProcessor $postFileProcessor,
        private readonly ErrorFactory $errorFactory,
        private readonly FilePathHelper $filePathHelper
    ) {
    }

    /**
     * @return array{system_errors: SystemError[], file_diffs: FileDiff[]}
     */
    public function process(File $file, Configuration $configuration): array
    {
        $systemErrorsAndFileDiffs = [
            Bridge::SYSTEM_ERRORS => [],
            Bridge::FILE_DIFFS => [],
        ];

        // 1. parse files to nodes
        try {
            $this->parseFileAndDecorateNodes($file);
        } catch (SystemErrorException $systemErrorException) {
            // we cannot process this file as the parsing and type resolving itself went wrong
            $systemErrorsAndFileDiffs[Bridge::SYSTEM_ERRORS] = [$systemErrorException->getSystemError()];

            return $systemErrorsAndFileDiffs;
        }

        $fileHasChanged = false;

        // 2. change nodes with Rectors
        $rectorWithLineChanges = null;
        do {
            $file->changeHasChanged(false);
            $this->fileProcessor->refactor($file, $configuration);

            // 3. apply post rectors
            $newStmts = $this->postFileProcessor->traverse($file->getNewStmts());
            // this is needed for new tokens added in "afterTraverse()"
            $file->changeNewStmts($newStmts);

            // 4. print to file or string
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
            $file->setFileDiff(
                $this->fileDiffFactory->createFileDiffWithLineChanges(
                    $file,
                    $file->getOriginalFileContent(),
                    $file->getFileContent(),
                    $rectorWithLineChanges
                )
            );
        }

        // return json here
        $fileDiff = $file->getFileDiff();
        if (! $fileDiff instanceof FileDiff) {
            return $systemErrorsAndFileDiffs;
        }

        $systemErrorsAndFileDiffs[Bridge::FILE_DIFFS] = [$fileDiff];
        return $systemErrorsAndFileDiffs;
    }

    public function supports(File $file, Configuration $configuration): bool
    {
        $filePathExtension = pathinfo($file->getFilePath(), PATHINFO_EXTENSION);
        return in_array($filePathExtension, $configuration->getFileExtensions(), true);
    }

    /**
     * @return string[]
     */
    public function getSupportedFileExtensions(): array
    {
        return ['php'];
    }

    /**
     * @throws SystemErrorException
     */
    private function parseFileAndDecorateNodes(File $file): void
    {
        $this->currentFileProvider->setFile($file);
        $this->notifyFile($file);

        try {
            $this->fileProcessor->parseFileInfoToLocalCache($file);
        } catch (ShouldNotHappenException $shouldNotHappenException) {
            throw $shouldNotHappenException;
        } catch (AnalysedCodeException $analysedCodeException) {
            // inform about missing classes in tests
            if (StaticPHPUnitEnvironment::isPHPUnitRun()) {
                throw $analysedCodeException;
            }

            $autoloadSystemError = $this->errorFactory->createAutoloadError(
                $analysedCodeException,
                $file->getFilePath()
            );
            throw new SystemErrorException($autoloadSystemError);
        } catch (Throwable $throwable) {
            if ($this->rectorOutputStyle->isVerbose() || StaticPHPUnitEnvironment::isPHPUnitRun()) {
                throw $throwable;
            }

            $relativeFilePath = $this->filePathHelper->relativePath($file->getFilePath());
            $systemError = new SystemError($throwable->getMessage(), $relativeFilePath, $throwable->getLine());

            throw new SystemErrorException($systemError);
        }
    }

    private function printFile(File $file, Configuration $configuration): void
    {
        $filePath = $file->getFilePath();
        if ($this->removedAndAddedFilesCollector->isFileRemoved($filePath)) {
            // skip, because this file exists no more
            return;
        }

        // only save to string first, no need to print to file when not needed
        $newContent = $this->formatPerservingPrinter->printParsedStmstAndTokensToString($file);

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
            $originalFileContent = $file->getOriginalFileContent();
            $ltrimOriginalFileContent = ltrim($originalFileContent);

            if ($ltrimOriginalFileContent === $newContent) {
                return;
            }

            $cleanOriginalContent = Strings::replace($ltrimOriginalFileContent, self::OPEN_TAG_SPACED_REGEX, '<?php');
            $cleanNewContent = Strings::replace($newContent, self::OPEN_TAG_SPACED_REGEX, '<?php');

            /**
             * Handle space before <?php wiped on print format preserving
             * On inside content level
             */
            if ($cleanOriginalContent === $cleanNewContent) {
                return;
            }
        }

        if (! $configuration->isDryRun()) {
            $this->formatPerservingPrinter->dumpFile($file->getFilePath(), $newContent);
        }

        $file->changeFileContent($newContent);
    }

    private function notifyFile(File $file): void
    {
        if (! $this->rectorOutputStyle->isVerbose()) {
            return;
        }

        $relativeFilePath = $this->filePathHelper->relativePath($file->getFilePath());
        $this->rectorOutputStyle->writeln($relativeFilePath);
    }
}
