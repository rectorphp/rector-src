<?php

declare(strict_types=1);

namespace Rector\NodeCollector;

use Nette\Utils\Strings;
use PHPStan\PhpDoc\ResolvedPhpDocBlock;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;

final class StaticAnalyzer
{
    public function __construct(
        private ReflectionProvider $reflectionProvider
    ) {
    }

    public function isStaticMethod(string $methodName, string $className): bool
    {
        if (! $this->reflectionProvider->hasClass($className)) {
            return false;
        }

        $classReflection = $this->reflectionProvider->getClass($className);

        if ($classReflection->hasNativeMethod($methodName)) {
            $methodReflection = $classReflection->getNativeMethod($methodName);
            if ($methodReflection->isStatic()) {
                return true;
            }
        }

        // could be static in doc type magic
        // @see https://regex101.com/r/tlvfTB/1
        return $this->hasStaticAnnotation($methodName, $classReflection);
    }

    private function hasStaticAnnotation(string $methodName, ClassReflection $classReflection): bool
    {
        $resolvedPhpDocBlock = $classReflection->getResolvedPhpDoc();
        if (! $resolvedPhpDocBlock instanceof ResolvedPhpDocBlock) {
            return false;
        }

        // @see https://regex101.com/r/7Zkej2/1
        return (bool) Strings::match(
            $resolvedPhpDocBlock->getPhpDocString(),
            '#@method\s*static\s*((([\w\|\\\\]+)|\$this)*+(\[\])*)*\s+\b' . $methodName . '\b#'
        );
    }
}
