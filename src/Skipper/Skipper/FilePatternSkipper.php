<?php

declare(strict_types=1);

namespace Rector\Skipper\Skipper;

use PhpParser\Node;
use Rector\Skipper\Matcher\FileInfoMatcher;

final readonly class FilePatternSkipper implements FileNodeSkipperInterface
{
    public function __construct(
        private FileInfoMatcher $fileInfoMatcher,
        private string $pattern,
    ) {
    }

    public function shouldSkip(string $fileName, ?Node $node): bool
    {
        return $this->fileInfoMatcher->doesFileMatchPattern($fileName, $this->pattern);
    }
}
