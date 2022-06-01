<?php

declare(strict_types=1);

namespace Rector\Php80\NodeAnalyzer;

use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassConst;
use Rector\NodeTypeResolver\NodeTypeResolver;

final class EnumConstListClassDetector
{
    public function __construct(
        private readonly NodeTypeResolver $nodeTypeResolver
    ) {
    }

    public function detect(Class_ $class): bool
    {
        $classConstants = $class->getConstants();

        // must have at least 2 constants, otherwise probably not enum
        if (count($classConstants) < 2) {
            return false;
        }

        // only constants are allowed, nothing else
        if (count($class->stmts) !== count($classConstants)) {
            return false;
        }

        // all constant must be public
        foreach ($classConstants as $classConstant) {
            if (! $classConstant->isPublic()) {
                return false;
            }
        }

        // all constants must have exactly 1 value
        foreach ($classConstants as $classConstant) {
            if (count($classConstant->consts) !== 1) {
                return false;
            }
        }

        $constantUniqueTypeCount = $this->resolveConstantUniqueTypeCount($classConstants);
        // must be exactly 1 type
        return $constantUniqueTypeCount === 1;
    }

    /**
     * @param ClassConst[] $classConsts
     */
    private function resolveConstantUniqueTypeCount(array $classConsts): int
    {
        $typeClasses = [];

        // all constants must have same type
        foreach ($classConsts as $classConst) {
            $const = $classConst->consts[0];
            $constantType = $this->nodeTypeResolver->getType($const->value);
            $typeClasses[] = $constantType::class;
        }

        $uniqueTypeClasses = array_unique($typeClasses);
        return count($uniqueTypeClasses);
    }
}
