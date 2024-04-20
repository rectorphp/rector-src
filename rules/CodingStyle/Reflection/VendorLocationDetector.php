<?php

declare(strict_types=1);

namespace Rector\CodingStyle\Reflection;

use PHPStan\Reflection\MethodReflection;
use Rector\FileSystem\FilePathHelper;
use PHPStan\Reflection\FunctionReflection;

final readonly class VendorLocationDetector
{
    public function __construct(
        private FilePathHelper $filePathHelper
    ) {
    }

    public function detectMethodReflection(MethodReflection|FunctionReflection $reflection): bool
    {
        if ($reflection instanceof MethodReflection) {
            $declaringClassReflection = $reflection->getDeclaringClass();
            $fileName = $declaringClassReflection->getFileName();
        } else {
            $fileName = $reflection->getFileName();
        }

        // probably internal
        if ($fileName === null) {
            return false;
        }

        $normalizedFileName = $this->filePathHelper->normalizePathAndSchema($fileName);
        return str_contains($normalizedFileName, '/vendor/');
    }
}
