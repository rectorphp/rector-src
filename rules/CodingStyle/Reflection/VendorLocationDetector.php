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

    private function detect(?string $fileName = null): bool
    {
        // probably internal
        if ($fileName === null) {
            return false;
        }

        $normalizedFileName = $this->filePathHelper->normalizePathAndSchema($fileName);
        return str_contains($normalizedFileName, '/vendor/');
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
}
