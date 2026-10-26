<?php

declare(strict_types=1);

namespace Rector\Reporting;

use Nette\Utils\FileSystem;
use Rector\Application\FileProcessor;
use Rector\Application\Provider\CurrentFileProvider;
use Rector\FileSystem\FilePathHelper;
use Rector\Skipper\Skipper\Skipper;
use Rector\ValueObject\Application\File;
use Rector\ValueObject\Configuration;

/**
 * Re-runs each rule-scoped skip on its exact file with skipping off. A skip whose rule still does
 * not change the file never prevented anything and can be removed. The re-processing is forced to a
 * dry run, so it never writes, no matter the mode of the real run.
 *
 * @see \Rector\Tests\Reporting\NeededSkipVerifierTest
 */
final readonly class NeededSkipVerifier
{
    public function __construct(
        private Skipper $skipper,
        private FileProcessor $fileProcessor,
        private CurrentFileProvider $currentFileProvider,
        private FilePathHelper $filePathHelper,
    ) {
    }

    /**
     * Unnecessary skips grouped under their rule, ready for "->listing()".
     *
     * @return string[]
     */
    public function verify(): array
    {
        $skippedFilePathsByRule = $this->skipper->provideSkippedFilePathsByRule();
        if ($skippedFilePathsByRule === []) {
            return [];
        }

        $changedRulesByFilePath = $this->resolveChangedRulesByFilePath($skippedFilePathsByRule);

        $unnecessarySkips = [];
        foreach ($skippedFilePathsByRule as $rectorClass => $filePaths) {
            $unnecessaryRelativePaths = [];
            foreach ($filePaths as $filePath) {
                // file could not be re-processed (e.g. parse error), keep the skip to stay safe
                if (! array_key_exists($filePath, $changedRulesByFilePath)) {
                    continue;
                }

                // the rule changed the file, so the skip really prevents a change - keep it
                if (in_array($rectorClass, $changedRulesByFilePath[$filePath], true)) {
                    continue;
                }

                $unnecessaryRelativePaths[] = $this->filePathHelper->relativePath($filePath);
            }

            if ($unnecessaryRelativePaths === []) {
                continue;
            }

            // rule on its own line, with each unnecessary path nested below it as a sub-item
            $unnecessarySkips[] = $rectorClass . ':' . "\n     * " . implode("\n     * ", $unnecessaryRelativePaths);
        }

        return $unnecessarySkips;
    }

    /**
     * @param array<class-string, string[]> $skippedFilePathsByRule
     * @return array<string, string[]> real file path => rule classes that changed it
     */
    private function resolveChangedRulesByFilePath(array $skippedFilePathsByRule): array
    {
        $uniqueFilePaths = [];
        foreach ($skippedFilePathsByRule as $filePaths) {
            foreach ($filePaths as $filePath) {
                $uniqueFilePaths[$filePath] = true;
            }
        }

        // force a dry run, so re-processing detects the change but never writes the skipped file
        $dryRunConfiguration = new Configuration(isDryRun: true);

        $this->skipper->startVerifyingSkips();

        $changedRulesByFilePath = [];
        try {
            foreach (array_keys($uniqueFilePaths) as $filePath) {
                $file = new File($filePath, FileSystem::read($filePath));
                $this->currentFileProvider->setFile($file);

                $fileProcessResult = $this->fileProcessor->processFile($file, $dryRunConfiguration);

                // a broken file is not verifiable, leave it out so its skips are kept
                if ($fileProcessResult->getSystemErrors() !== []) {
                    continue;
                }

                $fileDiff = $fileProcessResult->getFileDiff();
                $changedRulesByFilePath[$filePath] = $fileDiff?->getRectorClasses() ?? [];
            }
        } finally {
            $this->skipper->stopVerifyingSkips();
        }

        return $changedRulesByFilePath;
    }
}
