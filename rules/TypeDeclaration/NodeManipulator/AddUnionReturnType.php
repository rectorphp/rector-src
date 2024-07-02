<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\NodeManipulator;

use PhpParser\Node;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PHPStan\Analyser\Scope;
use PHPStan\Type\UnionType;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\PHPStanStaticTypeMapper\TypeMapper\UnionTypeMapper;
use Rector\TypeDeclaration\TypeInferer\ReturnTypeInferer;
use Rector\VendorLocker\NodeVendorLocker\ClassMethodReturnTypeOverrideGuard;

final readonly class AddUnionReturnType
{
    public function __construct(
        private ReturnTypeInferer $returnTypeInferer,
        private UnionTypeMapper $unionTypeMapper,
        private ClassMethodReturnTypeOverrideGuard $classMethodReturnTypeOverrideGuard,
    ) {
    }

    /**
     * @template TCallLike as ClassMethod|Function_|Closure
     *
     * @param TCallLike $node
     * @return TCallLike|null
     */
    public function add(ClassMethod|Function_|Closure $node, Scope $scope): ClassMethod|Function_|Closure|null
    {
        if ($node->stmts === null) {
            return null;
        }

        // type is already known
        if ($node->returnType instanceof Node) {
            return null;
        }

        if ($node instanceof ClassMethod && $this->classMethodReturnTypeOverrideGuard->shouldSkipClassMethod(
            $node,
            $scope
        )) {
            return null;
        }

        $inferReturnType = $this->returnTypeInferer->inferFunctionLike($node);
        if (! $inferReturnType instanceof UnionType) {
            return null;
        }

        $returnType = $this->unionTypeMapper->mapToPhpParserNode($inferReturnType, TypeKind::RETURN);
        if (! $returnType instanceof Node) {
            return null;
        }

        $node->returnType = $returnType;
        return $node;
    }
}
