<?php

declare(strict_types=1);

namespace Rector\Skipper\SkipCriteriaResolver;

use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\FileSystem\FilePathHelper;
use Rector\Testing\PHPUnit\StaticPHPUnitEnvironment;

/**
 * @see \Rector\Tests\Skipper\SkipCriteriaResolver\SkippedPathsResolver\SkippedPathsResolverTest
 */
final class SkippedPathsResolver
{
    /**
     * @var null|string[]
     */
    private ?array $skippedPaths = null;

    public function __construct(
        private readonly FilePathHelper $filePathHelper
    ) {
    }

    /**
     * @return string[]
     */
    public function resolve(): array
    {
        // disable cache in tests
        if (StaticPHPUnitEnvironment::isPHPUnitRun()) {
            $this->skippedPaths = null;
        }

        // already filled, even empty array
        if ($this->skippedPaths !== null) {
            return $this->skippedPaths;
        }

        $skip = SimpleParameterProvider::provideArrayParameter(Option::SKIP);
        $this->skippedPaths = [];

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
