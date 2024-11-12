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
use PhpParser\Node\Stmt\Property;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;
use Rector\NodeAnalyzer\ExprAnalyzer;
use Rector\Php\ReservedKeywordAnalyzer;
use Rector\PhpParser\AstResolver;
use Rector\PHPStan\ScopeFetcher;
use Rector\Rector\AbstractRector;
use Rector\Reflection\ReflectionResolver;
use Rector\TypeDeclaration\TypeInferer\PropertyTypeInferer\AllAssignNodePropertyTypeInferer;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\CodeQuality\Rector\Empty_\SimplifyEmptyCheckOnEmptyArrayRector\SimplifyEmptyCheckOnEmptyArrayRectorTest
 */
final class SimplifyEmptyCheckOnEmptyArrayRector extends AbstractRector
{
    public function __construct(
        private readonly ExprAnalyzer $exprAnalyzer,
        private readonly ReflectionResolver $reflectionResolver,
        private readonly AstResolver $astResolver,
        private readonly AllAssignNodePropertyTypeInferer $allAssignNodePropertyTypeInferer,
        private readonly ReservedKeywordAnalyzer $reservedKeywordAnalyzer
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Simplify empty() functions calls on empty arrays',
            [new CodeSample(
                <<<'CODE_SAMPLE'
$array = [];

if (empty($values)) {
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
$array = [];

if ([] === $values) {
}
CODE_SAMPLE
            )]
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
    public function refactor(Node $node): ?Node
    {
        $scope = ScopeFetcher::fetch($node);
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

    private function isAllowedVariable(Variable $variable): bool
    {
        if (is_string($variable->name) && $this->reservedKeywordAnalyzer->isNativeVariable($variable->name)) {
            return false;
        }

        return ! $this->exprAnalyzer->isNonTypedFromParam($variable);
    }

    private function isAllowedExpr(Expr $expr, Scope $scope): bool
    {
        if (! $scope->getType($expr)->isArray()->yes()) {
            return false;
        }

        if ($expr instanceof Variable) {
            return $this->isAllowedVariable($expr);
        }

        if (! $expr instanceof PropertyFetch && ! $expr instanceof StaticPropertyFetch) {
            return false;
        }

        if (! $expr->name instanceof Identifier) {
            return false;
        }

        $classReflection = $this->reflectionResolver->resolveClassReflectionSourceObject($expr);
        if (! $classReflection instanceof ClassReflection) {
            return false;
        }

        $propertyName = $expr->name->toString();
        if (! $classReflection->hasNativeProperty($propertyName)) {
            return false;
        }

        $phpPropertyReflection = $classReflection->getNativeProperty($propertyName);
        $nativeType = $phpPropertyReflection->getNativeType();

        if (! $nativeType instanceof MixedType) {
            return $nativeType->isArray()
                ->yes();
        }

        $property = $this->astResolver->resolvePropertyFromPropertyReflection($phpPropertyReflection);

        /**
         * Skip property promotion mixed type for now, as:
         *
         *   - require assign in default param check
         *   - check all assign of property promotion params under the class
         */
        if (! $property instanceof Property) {
            return false;
        }

        $type = $this->allAssignNodePropertyTypeInferer->inferProperty($property, $classReflection, $this->file);
        if (! $type instanceof Type) {
            return false;
        }

        return $type->isArray()
            ->yes();
    }
}
