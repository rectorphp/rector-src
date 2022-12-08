<?php

declare(strict_types=1);

namespace Rector\CodeQuality\Rector\Empty_;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\BinaryOp\NotIdentical;
use PhpParser\Node\Expr\BooleanNot;
use PhpParser\Node\Expr\Empty_;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\ArrayType;
use PHPStan\Type\MixedType;
use Rector\Core\NodeAnalyzer\ExprAnalyzer;
use Rector\Core\PhpParser\AstResolver;
use Rector\Core\Rector\AbstractScopeAwareRector;
use Rector\Core\Reflection\ReflectionResolver;
use Rector\TypeDeclaration\TypeInferer\PropertyTypeInferer\AllAssignNodePropertyTypeInferer;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\CodeQuality\Rector\Empty_\SimplifyEmptyCheckOnEmptyArrayRectorTest\SimplifyEmptyCheckOnEmptyArrayRectorTest
 */
final class SimplifyEmptyCheckOnEmptyArrayRector extends AbstractScopeAwareRector
{
    public function __construct(
        private readonly ExprAnalyzer $exprAnalyzer,
        private readonly ReflectionResolver $reflectionResolver,
        private readonly AstResolver $astResolver,
        private readonly AllAssignNodePropertyTypeInferer $allAssignNodePropertyTypeInferer
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Simplify `empty` functions calls on empty arrays',
            [new CodeSample('$array = []; if(empty($values))', '$array = []; if([] === $values)')]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Empty_::class, BooleanNot::class];
    }

    /**
     * @param Empty_|BooleanNot $node $node
     */
    public function refactorWithScope(Node $node, Scope $scope): ?Node
    {
        if ($node instanceof BooleanNot) {
            if ($node->expr instanceof Empty_ && $this->isAllowedExpr($node->expr->expr, $scope)) {
                return new NotIdentical($node->expr->expr, new Array_());
            }

            return null;
        }

        if (! $this->isAllowedExpr($node->expr, $scope)) {
            return null;
        }

        return new Identical($node->expr, new Array_());
    }

    private function isAllowedExpr(Expr $expr, Scope $scope): bool
    {
        if (! $scope->getType($expr) instanceof ArrayType) {
            return false;
        }

        if ($expr instanceof Variable) {
            return ! $this->exprAnalyzer->isNonTypedFromParam($expr);
        }

        if (! $expr instanceof PropertyFetch && ! $expr instanceof StaticPropertyFetch) {
            return false;
        }

        if (! $expr->name instanceof Identifier) {
            return false;
        }

        $classReflection = $this->reflectionResolver->resolveClassReflection($expr);
        if (! $classReflection instanceof ClassReflection) {
            return false;
        }

        $propertyName = $expr->name->toString();
        if (! $classReflection->hasNativeProperty($propertyName)) {
            return false;
        }

        $phpPropertyReflection = $classReflection->getNativeProperty($propertyName);
        $nativeType = $phpPropertyReflection->getNativeType();

        if ($nativeType instanceof MixedType) {
            $property = $this->astResolver->resolvePropertyFromPropertyReflection($phpPropertyReflection);
            $type = $this->allAssignNodePropertyTypeInferer->inferProperty($property);
            return $type instanceof ArrayType;
        }

        return $nativeType instanceof ArrayType;
    }
}
