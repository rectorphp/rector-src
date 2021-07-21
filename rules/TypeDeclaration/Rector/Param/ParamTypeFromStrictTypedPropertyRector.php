<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\Param;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\NullableType;
use PhpParser\Node\Param;
use PhpParser\Node\UnionType;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\Php\PhpPropertyReflection;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\Reflection\ReflectionResolver;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PHPStanStaticTypeMapper\ValueObject\TypeKind;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\Param\ParamTypeFromStrictTypedPropertyRector\ParamTypeFromStrictTypedPropertyRectorTest
 */
final class ParamTypeFromStrictTypedPropertyRector extends AbstractRector implements MinPhpVersionInterface
{
    public function __construct(
        private ReflectionResolver $reflectionResolver
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add param type from $param set to typed property', [
            new CodeSample(
                <<<'CODE_SAMPLE'
final class SomeClass
{
    private int $age;

    public function setAge($age)
    {
        $this->age = $age;
    }
}
CODE_SAMPLE

                ,
                <<<'CODE_SAMPLE'
final class SomeClass
{
    private int $age;

    public function setAge(int $age)
    {
        $this->age = $age;
    }
}
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Param::class];
    }

    /**
     * @param Param $node
     */
    public function refactor(Node $node): ?Node
    {
        $parent = $node->getAttribute(AttributeKey::PARENT_NODE);
        if (! $parent instanceof FunctionLike) {
            return null;
        }

        return $this->decorateParamWithType($parent, $node);
    }

    public function decorateParamWithType(FunctionLike $functionLike, Param $param): ?Param
    {
        if ($param->type !== null) {
            return null;
        }

        $originalParamType = $this->resolveParamOriginalType($param);

        $paramName = $this->getName($param);

        /** @var Assign[] $assigns */
        $assigns = $this->betterNodeFinder->findInstanceOf((array) $functionLike->getStmts(), Assign::class);
        foreach ($assigns as $assign) {
            if (! $this->nodeComparator->areNodesEqual($assign->expr, $param->var)) {
                continue;
            }

            if (! $assign->var instanceof PropertyFetch) {
                continue;
            }

            if ($this->hasTypeChangedBeforeAssign($assign, $paramName, $originalParamType)) {
                return null;
            }

            $singlePropertyTypeNode = $this->matchPropertySingleTypeNode($assign->var);
            if (! $singlePropertyTypeNode instanceof Node) {
                return null;
            }

            $param->type = $singlePropertyTypeNode;
            return $param;
        }

        return null;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::TYPED_PROPERTIES;
    }

    /**
     * @return Node\Name|UnionType|NullableType|null
     */
    private function matchPropertySingleTypeNode(PropertyFetch $propertyFetch): ?Node
    {
        $phpPropertyReflection = $this->reflectionResolver->resolvePropertyReflectionFromPropertyFetch($propertyFetch);
        if (! $phpPropertyReflection instanceof PhpPropertyReflection) {
            return null;
        }

        $propertyType = $phpPropertyReflection->getNativeType();

        if ($propertyType instanceof MixedType) {
            return null;
        }

        if ($propertyType instanceof \PHPStan\Type\UnionType) {
            return null;
        }

        if ($propertyType instanceof NullableType) {
            return null;
        }

        return $this->staticTypeMapper->mapPHPStanTypeToPhpParserNode($propertyType, TypeKind::PROPERTY());
    }

    private function hasTypeChangedBeforeAssign(Assign $assign, string $paramName, Type $originalType): bool
    {
        $scope = $assign->getAttribute(AttributeKey::SCOPE);
        if (! $scope instanceof Scope) {
            return false;
        }

        if (! $scope->hasVariableType($paramName)->yes()) {
            return false;
        }

        $currentParamType = $scope->getVariableType($paramName);
        return ! $currentParamType->equals($originalType);
    }

    private function resolveParamOriginalType(Param $param): Type
    {
        $scope = $param->getAttribute(AttributeKey::SCOPE);
        if (! $scope instanceof Scope) {
            return new MixedType();
        }

        $paramName = $this->getName($param);
        if (! $scope->hasVariableType($paramName)->yes()) {
            return new MixedType();
        }

        return $scope->getVariableType($paramName);
    }
}
