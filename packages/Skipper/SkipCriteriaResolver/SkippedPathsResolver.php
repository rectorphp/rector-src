<?php

declare(strict_types=1);

namespace Rector\Skipper\SkipCriteriaResolver;

use Rector\Core\Configuration\Option;
use Rector\Core\Configuration\Parameter\SimpleParameterProvider;
use Rector\Core\FileSystem\FilePathHelper;
use Rector\Testing\PHPUnit\StaticPHPUnitEnvironment;

/**
 * @see \Rector\Tests\Skipper\SkipCriteriaResolver\SkippedPathsResolver\SkippedPathsResolverTest
 */
final class SkippedPathsResolver
{
    /**
     * @var string[]
     */
    private array $skippedPaths = [];

    public function __construct(
        private readonly FilePathHelper $filePathHelper
    ) {
    }

    /**
     * @return string[]
     */
    public function resolve(): array
    {
        if (StaticPHPUnitEnvironment::isPHPUnitRun()) {
            // disable cache in tests
            $this->skippedPaths = [];
        }

        if ($this->skippedPaths !== []) {
            return $this->skippedPaths;
        }

        $skip = SimpleParameterProvider::provideArrayParameter(Option::SKIP);

        foreach ($skip as $key => $value) {
            if (! is_int($key)) {
                continue;
            }

            if (\str_contains((string) $value, '*')) {
                $this->skippedPaths[] = $this->filePathHelper->normalizePathAndSchema($value);
                continue;
            }

            if (file_exists($value)) {
                $this->skippedPaths[] = $this->filePathHelper->normalizePathAndSchema($value);
            }
        }

        return $this->skippedPaths;
    }
}
