<?php

declare(strict_types=1);

namespace Rector\CodingStyle\Reflection;

use PHPStan\Reflection\FunctionReflection;
use PHPStan\Reflection\MethodReflection;
use Rector\FileSystem\FilePathHelper;

final readonly class VendorLocationDetector
{
    public function __construct(
        private FilePathHelper $filePathHelper
    ) {
    }

    public function detectMethodReflection(MethodReflection $reflection): bool
    {
        $declaringClassReflection = $reflection->getDeclaringClass();
        $fileName = $declaringClassReflection->getFileName();

        return $this->detect($fileName);
    }

    public function detectFunctionReflection(FunctionReflection $reflection): bool
    {
        $fileName = $reflection->getFileName();

        return $this->detect($fileName);
    }

    private function detect(?string $fileName = null): bool
    {
        // probably internal
        if ($fileName === null) {
            return false;
        }

        $normalizedFileName = $this->filePathHelper->normalizePathAndSchema($fileName);
        return str_contains($normalizedFileName, '/vendor/');
    }
}
