<?php

declare(strict_types=1);

namespace Rector\Php84\NodeFinder;

use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\TypeDeclaration\NodeAnalyzer\ClassMethodAndPropertyAnalyzer;

final readonly class SetterAndGetterFinder
{
    public function __construct(
        private ClassMethodAndPropertyAnalyzer $classMethodAndPropertyAnalyzer
    ) {
    }

    /**
     * @return ClassMethod[]
     */
    public function findGetterAndSetterClassMethods(Class_ $class, string $propertyName): array
    {
        $classMethods = [];

        $getterClassMethod = $this->findGetterClassMethod($class, $propertyName);
        if ($getterClassMethod instanceof ClassMethod) {
            $classMethods[] = $getterClassMethod;
        }

        $setterClassMethod = $this->findSetterClassMethod($class, $propertyName);
        if ($setterClassMethod instanceof ClassMethod) {
            $classMethods[] = $setterClassMethod;
        }

        return $classMethods;
    }

    public function findGetterClassMethod(Class_ $class, string $propertyName): ?ClassMethod
    {
        foreach ($class->getMethods() as $classMethod) {
            if (! $this->classMethodAndPropertyAnalyzer->hasPropertyFetchReturn($classMethod, $propertyName)) {
                continue;
            }

            return $classMethod;
        }

        return null;
    }

    public function findSetterClassMethod(Class_ $class, string $propertyName): ?ClassMethod
    {
        foreach ($class->getMethods() as $classMethod) {

            if ($classMethod->isMagic()) {
                continue;
            }

            if (! $this->classMethodAndPropertyAnalyzer->hasOnlyPropertyAssign($classMethod, $propertyName)) {
                continue;
            }

            return $classMethod;
        }

        return null;
    }
}
