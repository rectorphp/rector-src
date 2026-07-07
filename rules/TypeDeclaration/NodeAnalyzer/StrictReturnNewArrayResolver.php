<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\NodeAnalyzer;

use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Function_;
use PHPStan\Type\Accessory\AccessoryArrayListType;
use PHPStan\Type\ArrayType;
use PHPStan\Type\Constant\ConstantArrayType;
use PHPStan\Type\IntersectionType;
use PHPStan\Type\MixedType;
use PHPStan\Type\NeverType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\BetterPhpDocParser\PhpDocManipulator\PhpDocTypeChanger;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\PhpParser\Comparing\NodeComparator;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\TypeDeclaration\TypeInferer\ReturnTypeInferer;

/**
 * Shared array return type resolution for ReturnTypeFromStrictNewArrayRector
 * and PrivateMethodReturnTypeFromStrictNewArrayRector.
 */
final readonly class StrictReturnNewArrayResolver
{
    public function __construct(
        private PhpDocTypeChanger $phpDocTypeChanger,
        private ReturnTypeInferer $returnTypeInferer,
        private PhpDocInfoFactory $phpDocInfoFactory,
        private BetterNodeFinder $betterNodeFinder,
        private ReturnAnalyzer $returnAnalyzer,
        private NodeComparator $nodeComparator,
        private NodeTypeResolver $nodeTypeResolver,
        private NodeNameResolver $nodeNameResolver
    ) {
    }

    /**
     * @return ClassMethod|Function_|null the passed node when an array return type was added, null otherwise
     */
    public function resolve(ClassMethod|Function_ $node): ClassMethod|Function_|null
    {
        // 1. is variable instantiated with array
        $stmts = $node->stmts;
        if ($stmts === null) {
            return null;
        }

        $variables = $this->matchArrayAssignedVariable($stmts);
        if ($variables === []) {
            return null;
        }

        $returns = $this->betterNodeFinder->findReturnsScoped($node);
        if (! $this->returnAnalyzer->hasOnlyReturnWithExpr($node, $returns)) {
            return null;
        }

        $variables = $this->matchVariableNotOverriddenByNonArray($node, $variables);
        if ($variables === []) {
            return null;
        }

        if (count($returns) > 1) {
            $returnType = $this->returnTypeInferer->inferFunctionLike($node);
            return $this->processAddArrayReturnType($node, $returnType);
        }

        $onlyReturn = $returns[0];
        if (! $onlyReturn->expr instanceof Variable) {
            return null;
        }

        if (! $this->nodeComparator->isNodeEqual($onlyReturn->expr, $variables)) {
            return null;
        }

        $returnType = $this->nodeTypeResolver->getNativeType($onlyReturn->expr);
        return $this->processAddArrayReturnType($node, $returnType);
    }

    private function processAddArrayReturnType(
        ClassMethod|Function_ $node,
        Type $returnType
    ): ClassMethod|Function_|null {
        if (! $returnType->isArray()->yes()) {
            return null;
        }

        // always returns array
        $node->returnType = new Identifier('array');

        // add more precise array type if suitable
        if ($this->shouldAddReturnArrayDocType($returnType)) {
            $this->changeReturnType($node, $returnType);
        }

        return $node;
    }

    private function changeReturnType(ClassMethod|Function_ $node, Type $arrayType): void
    {
        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);

        // skip already filled type, on purpose
        if (! $phpDocInfo->getReturnType() instanceof MixedType) {
            return;
        }

        // can handle only exactly 1-type array
        if ($arrayType instanceof ConstantArrayType && count($arrayType->getValueTypes()) !== 1) {
            return;
        }

        $itemType = $arrayType->getIterableValueType();
        if ($itemType instanceof IntersectionType) {
            $narrowArrayType = $arrayType;
        } else {
            $narrowArrayType = new ArrayType(new MixedType(), $itemType);
        }

        if ($arrayType->isList()->yes()) {
            $narrowArrayType = TypeCombinator::intersect($narrowArrayType, new AccessoryArrayListType());
        }

        $this->phpDocTypeChanger->changeReturnType($node, $phpDocInfo, $narrowArrayType);
    }

    /**
     * @param Variable[] $variables
     * @return Variable[]
     */
    private function matchVariableNotOverriddenByNonArray(ClassMethod|Function_ $functionLike, array $variables): array
    {
        // is variable overridden?
        /** @var Assign[] $assigns */
        $assigns = $this->betterNodeFinder->findInstancesOfInFunctionLikeScoped($functionLike, Assign::class);
        foreach ($assigns as $assign) {
            if (! $assign->var instanceof Variable) {
                continue;
            }

            foreach ($variables as $key => $variable) {
                if (! $this->nodeNameResolver->areNamesEqual($assign->var, $variable)) {
                    continue;
                }

                if ($assign->expr instanceof Array_) {
                    continue;
                }

                $nativeType = $this->nodeTypeResolver->getNativeType($assign->expr);
                if (! $nativeType->isArray()->yes()) {
                    unset($variables[$key]);
                }
            }
        }

        return $variables;
    }

    /**
     * @param Stmt[] $stmts
     * @return Variable[]
     */
    private function matchArrayAssignedVariable(array $stmts): array
    {
        $variables = [];
        foreach ($stmts as $stmt) {
            if (! $stmt instanceof Expression) {
                continue;
            }

            if (! $stmt->expr instanceof Assign) {
                continue;
            }

            $assign = $stmt->expr;
            if (! $assign->var instanceof Variable) {
                continue;
            }

            $nativeType = $this->nodeTypeResolver->getNativeType($assign->expr);
            if ($nativeType->isArray()->yes()) {
                $variables[] = $assign->var;
            }
        }

        return $variables;
    }

    private function shouldAddReturnArrayDocType(Type $arrayType): bool
    {
        if ($arrayType instanceof ConstantArrayType) {
            if ($arrayType->getIterableValueType() instanceof NeverType) {
                return false;
            }

            // handle only simple arrays
            if (! $arrayType->getIterableKeyType()->isInteger()->yes()) {
                return false;
            }
        }

        return true;
    }
}
