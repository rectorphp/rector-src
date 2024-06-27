<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\NodeManipulator;

use PhpParser\Node;
use PhpParser\Node\Expr\Cast;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Return_;
use PHPStan\Analyser\Scope;
use PHPStan\Type\UnionType;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\StaticTypeMapper\StaticTypeMapper;
use Rector\TypeDeclaration\TypeInferer\ReturnTypeInferer;
use Rector\VendorLocker\NodeVendorLocker\ClassMethodReturnTypeOverrideGuard;

final readonly class AddReturnTypeFromCast
{
    public function __construct(
        private BetterNodeFinder $betterNodeFinder,
        private ReturnTypeInferer $returnTypeInferer,
        private StaticTypeMapper $staticTypeMapper,
        private ClassMethodReturnTypeOverrideGuard $classMethodReturnTypeOverrideGuard,
    ) {
    }

    public function add(ClassMethod|Function_|Closure $node, Scope $scope): ClassMethod|Function_|Closure|null
    {
        if ($node->returnType !== null) {
            return null;
        }

        if ($node instanceof ClassMethod && $this->classMethodReturnTypeOverrideGuard->shouldSkipClassMethod(
            $node,
            $scope
        )) {
            return null;
        }

        $hasNonCastReturn = (bool) $this->betterNodeFinder->findFirstInFunctionLikeScoped(
            $node,
            static fn (Node $subNode): bool => $subNode instanceof Return_ && ! $subNode->expr instanceof Cast
        );

        if ($hasNonCastReturn) {
            return null;
        }

        $returnType = $this->returnTypeInferer->inferFunctionLike($node);
        if ($returnType instanceof UnionType || $returnType->isVoid()->yes()) {
            return null;
        }

        $returnTypeNode = $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode($returnType, TypeKind::RETURN);
        if (! $returnTypeNode instanceof Node) {
            return null;
        }

        $node->returnType = $returnTypeNode;
        return $node;
    }
}
