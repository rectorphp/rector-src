<?php

declare(strict_types=1);

namespace Rector\NodeManipulator;

use PhpParser\Node\Stmt\ClassMethod;
use Rector\NodeNameResolver\NodeNameResolver;

final readonly class FunctionLikeManipulator
{
    public function __construct(
        private NodeNameResolver $nodeNameResolver,
    ) {
    }

    /**
     * @return string[]
     */
    public function resolveParamNames(ClassMethod $classMethod): array
    {
        $paramNames = [];

        foreach ($classMethod->getParams() as $param) {
            $paramNames[] = $this->nodeNameResolver->getName($param);
        }

        return $paramNames;
    }
}
