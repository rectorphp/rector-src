<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\NodeTypeResolver;

use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Param;
use PHPStan\Analyser\Scope;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Contract\NodeTypeResolverInterface;
use Rector\NodeTypeResolver\Node\AttributeKey;

/**
 * @see \Rector\Tests\NodeTypeResolver\PerNodeTypeResolver\VariableTypeResolver\VariableTypeResolverTest
 *
 * @implements NodeTypeResolverInterface<Variable>
 */
final class VariableTypeResolver implements NodeTypeResolverInterface
{
    public function __construct(
        private NodeNameResolver $nodeNameResolver,
        private PhpDocInfoFactory $phpDocInfoFactory
    ) {
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
        return $phpDocInfo->getVarType();
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
        $parent = $variable->getAttribute(AttributeKey::PARENT_NODE);
        if (! $parent instanceof Node) {
            return null;
        }

        return $parent->getAttribute(AttributeKey::SCOPE);
    }
}
