<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\TypeAnalyzer;

use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Type\Type;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\NodeTypeResolver\PHPStan\Type\TypeFactory;
use Rector\TypeDeclaration\NodeAnalyzer\ReturnTypeAnalyzer\AlwaysStrictReturnAnalyzer;

final class StrictReturnClassConstReturnTypeAnalyzer
{
    public function __construct(
        private readonly AlwaysStrictReturnAnalyzer $alwaysStrictReturnAnalyzer,
        private readonly NodeTypeResolver $nodeTypeResolver,
        private readonly TypeFactory $typeFactory
    ) {
    }

    public function matchAlwaysReturnConstFetch(ClassMethod $classMethod): ?Type
    {
        $returns = $this->alwaysStrictReturnAnalyzer->matchAlwaysStrictReturns($classMethod);
        if ($returns === null) {
            return null;
        }

        $classConstFetchTypes = [];

        foreach ($returns as $return) {
            // @todo ~30 mins paid
            if (! $return->expr instanceof ClassConstFetch) {
                return null;
            }

            $classConstFetchTypes[] = $this->nodeTypeResolver->getType($return->expr);
        }

        return $this->typeFactory->createMixedPassedOrUnionType($classConstFetchTypes);
    }
}
