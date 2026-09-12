<?php

declare(strict_types=1);

namespace Rector\Utils\Duplicates\ValueObject;

final readonly class CodeCloneFile
{
    public function __construct(
        public string $filePath,
        public int $startLine,
        public int $endLine
    ) {
    }
}
