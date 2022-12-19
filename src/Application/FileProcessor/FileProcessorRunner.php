<?php

declare(strict_types=1);

namespace Rector\Core\Application\FileProcessor;

use Nette\Utils\FileSystem;
use PHPStan\Analyser\NodeScopeResolver;
use Rector\Core\Contract\Processor\FileProcessorInterface;
use Rector\Core\ValueObject\Application\File;
use Rector\Core\ValueObject\Configuration;
use Rector\Core\ValueObject\Error\SystemError;
use Rector\Core\ValueObject\Reporting\FileDiff;

final class FileProcessorRunner
{
    public $arrayParametersMerger;

    /**
     * @param FileProcessorInterface[] $fileProcessors
     */
    public function __construct(
        private readonly NodeScopeResolver $nodeScopeResolver,
        private readonly array $fileProcessors = []
    ) {
    }

    /**
     * @return array{system_errors: SystemError[], file_diffs: FileDiff[]}
     */
    public function run(array $filePaths, Configuration $configuration): array
    {
        // 1. allow PHPStan to work with static reflection on provided files
        $this->configurePHPStanNodeScopeResolver($filePaths);

        // 2. process files
        $errorAndFileDiffs = $this->processFiles($filePaths, $configuration, []);

        return $errorAndFileDiffs;
    }

    /**
     * @param array{system_errors: SystemError[], file_diffs: FileDiff[]}|mixed[] $errorAndFileDiffs
     * @return array{system_errors: SystemError[], file_diffs: FileDiff[]}
     */
    public function processFiles(array $filePaths, Configuration $configuration, array $errorAndFileDiffs): array
    {
        foreach ($filePaths as $filePath) {
            $file = new File($filePath, FileSystem::read($filePath));

            foreach ($this->fileProcessors as $fileProcessor) {
                if (! $fileProcessor->supports($file, $configuration)) {
                    continue;
                }

                $currentErrorsAndFileDiffs = $fileProcessor->process($file, $configuration);
                $errorAndFileDiffs = $this->arrayParametersMerger->merge(
                    $errorAndFileDiffs,
                    $currentErrorsAndFileDiffs
                );
            }
        }

        return $errorAndFileDiffs;
    }

    /**
     * @param string[] $filePaths
     */
    private function configurePHPStanNodeScopeResolver(array $filePaths): void
    {
        $phpFilePaths = array_filter(
            $filePaths,
            static fn (string $filePath): bool => str_ends_with($filePath, '.php')
        );
        $this->nodeScopeResolver->setAnalysedFiles($phpFilePaths);
    }
}
