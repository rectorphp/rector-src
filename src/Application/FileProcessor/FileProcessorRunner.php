<?php

declare(strict_types=1);

namespace Rector\Core\Application\FileProcessor;

use PHPStan\Analyser\NodeScopeResolver;
use Rector\Core\Contract\Processor\FileProcessorInterface;
use Rector\Core\ValueObject\Configuration;

final class FileProcessorRunner
{
    /**
     * @param FileProcessorInterface[] $fileProcessors
     */
    public function __construct(
        private readonly NodeScopeResolver $nodeScopeResolver,
        private readonly array $fileProcessors = [])
    {
    }

    public function run(array $filePaths, Configuration $configuration): array
    {
        // 1. allow PHPStan to work with static reflection on provided files
        $this->configurePHPStanNodeScopeResolver($filePaths);

        // todo: copy more re-usable code...

        return [];
    }

    /**
     * @param string[] $filePaths
     */
    private function configurePHPStanNodeScopeResolver(array $filePaths): void
    {
        $phpFilePaths = array_filter($filePaths, static fn (string $filePath): bool => str_ends_with($filePath, '.php'));
        $this->nodeScopeResolver->setAnalysedFiles($phpFilePaths);
    }
}
