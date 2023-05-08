<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\NodeTypeResolver;

use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Contract\NodeTypeResolverInterface;
use Rector\NodeTypeResolver\Node\AttributeKey;
use PHPStan\Reflection\ReflectionProvider;
use Rector\Core\Reflection\ReflectionResolver;
use PHPStan\Reflection\ClassReflection;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * @see \Rector\Tests\NodeTypeResolver\PerNodeTypeResolver\VariableTypeResolver\VariableTypeResolverTest
 *
 * @implements NodeTypeResolverInterface<Variable>
 */
final class VariableTypeResolver implements NodeTypeResolverInterface
{
    private readonly ReflectionResolver $reflectionResolver;

    public function __construct(
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
    ) {
    }

    #[Required]
    public function autowire(
        ReflectionResolver $reflectionResolver,
    ): void {
        $this->reflectionResolver = $reflectionResolver;
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeClasses(): array
    {
        return [Variable::class];
    }

    /**
     * @param Variable $node
     */
    public function resolve(Node $node): Type
    {
        $variableName = $this->nodeNameResolver->getName($node);
        if ($variableName === null) {
            return new MixedType();
        }

        $scopeType = $this->resolveTypesFromScope($node, $variableName);
        if (! $scopeType instanceof MixedType) {
            return $scopeType;
        }

        // get from annotation
        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);
        $varType = $phpDocInfo->getVarType();

        if (! $varType instanceof MixedType) {
            return $varType;
        }

        if ($variableName === 'this') {
            $classReflection = $this->reflectionResolver->resolveClassReflection($node);
            dump($classReflection);
            if (! $classReflection instanceof ClassReflection) {
                dump('here');
                return $varType;
            }

            return new \PHPStan\Type\ThisType($classReflection);
        }

        return $varType;
    }

    private function resolveTypesFromScope(Variable $variable, string $variableName): Type
    {
        $scope = $this->resolveNodeScope($variable);
        if (! $scope instanceof Scope) {
            return new MixedType();
        }

        if (! $scope->hasVariableType($variableName)->yes()) {
            return new MixedType();
        }

        // this → object type is easier to work with and consistent with the rest of the code
        return $scope->getVariableType($variableName);
    }

    private function resolveNodeScope(Variable $variable): ?Scope
    {
        $scope = $variable->getAttribute(AttributeKey::SCOPE);
        if ($scope instanceof Scope) {
            return $scope;
        }

        return $this->resolveFromParentNodes($variable);
    }

    private function resolveFromParentNodes(Variable $variable): ?Scope
    {
        $parentNode = $variable->getAttribute(AttributeKey::PARENT_NODE);
        if (! $parentNode instanceof Node) {
            return null;
        }

        return $parentNode->getAttribute(AttributeKey::SCOPE);
    }
}
