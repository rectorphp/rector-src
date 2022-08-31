<?php

declare(strict_types=1);

namespace Rector\Skipper\SkipCriteriaResolver;

use Rector\Core\Configuration\Option;
use Symplify\PackageBuilder\Parameter\ParameterProvider;
use Symplify\SmartFileSystem\Normalizer\PathNormalizer;

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
        private readonly ParameterProvider $parameterProvider,
        private readonly PathNormalizer $pathNormalizer
    ) {
    }

    /**
     * @return string[]
     */
    public function resolve(): array
    {
        if ($this->skippedPaths !== []) {
            return $this->skippedPaths;
        }

        $skip = $this->parameterProvider->provideArrayParameter(Option::SKIP);

        foreach ($skip as $key => $value) {
            if (! is_int($key)) {
                continue;
            }

            if (file_exists($value)) {
                $this->skippedPaths[] = $this->pathNormalizer->normalizePath($value);
                continue;
            }

            if (\str_contains((string) $value, '*')) {
                $this->skippedPaths[] = $this->pathNormalizer->normalizePath($value);
                continue;
            }
        }

        return $this->skippedPaths;
    }
}
