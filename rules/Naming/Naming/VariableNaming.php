<?php

declare(strict_types=1);

namespace Rector\Naming\Naming;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\Cast;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\NullsafeMethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Ternary;
use PhpParser\Node\Scalar\String_;
use PHPStan\Analyser\Scope;
use PHPStan\Type\ThisType;
use PHPStan\Type\Type;
use Rector\Naming\AssignVariableNameResolver\NewAssignVariableNameResolver;
use Rector\Naming\AssignVariableNameResolver\PropertyFetchAssignVariableNameResolver;
use Rector\Naming\Contract\AssignVariableNameResolverInterface;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\NodeTypeResolver;

/**
 * @api used in downgrade
 */
final class VariableNaming
{
    /**
     * @var AssignVariableNameResolverInterface[]
     */
    private array $assignVariableNameResolvers = [];

    public function __construct(
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly NodeTypeResolver $nodeTypeResolver,
        PropertyFetchAssignVariableNameResolver $propertyFetchAssignVariableNameResolver,
        NewAssignVariableNameResolver $newAssignVariableNameResolver,
    ) {
        $this->assignVariableNameResolvers = [
            $propertyFetchAssignVariableNameResolver,
            $newAssignVariableNameResolver,
        ];
    }

    /**
     * @api used in downgrade
     */
    public function createCountedValueName(string $valueName, ?Scope $scope): string
    {
        if (! $scope instanceof Scope) {
            return $valueName;
        }

        // make sure variable name is unique
        if (! $scope->hasVariableType($valueName)->yes()) {
            return $valueName;
        }

        // we need to add number suffix until the variable is unique
        $i = 2;
        $countedValueNamePart = $valueName;
        while ($scope->hasVariableType($valueName)->yes()) {
            $valueName = $countedValueNamePart . $i;
            ++$i;
        }

        return $valueName;
    }

    private function resolveFromNodeAndType(Node $node, Type $type): ?string
    {
        $variableName = $this->resolveBareFromNode($node);
        if ($variableName === null) {
            return null;
        }

        // adjust static to specific class
        if ($variableName === 'this' && $type instanceof ThisType) {
            $shortClassName = $this->nodeNameResolver->getShortName($type->getClassName());
            return lcfirst($shortClassName);
        }

        return $this->nodeNameResolver->getShortName($variableName);
    }

    private function resolveFromNode(Node $node): ?string
    {
        $nodeType = $this->nodeTypeResolver->getType($node);
        return $this->resolveFromNodeAndType($node, $nodeType);
    }

    private function resolveBareFromNode(Node $node): ?string
    {
        $unwrappedNode = $this->unwrapNode($node);

        if (! $unwrappedNode instanceof Node) {
            return null;
        }

        foreach ($this->assignVariableNameResolvers as $assignVariableNameResolver) {
            if ($assignVariableNameResolver->match($unwrappedNode)) {
                return $assignVariableNameResolver->resolve($unwrappedNode);
            }
        }

        if ($unwrappedNode instanceof MethodCall || $unwrappedNode instanceof NullsafeMethodCall || $unwrappedNode instanceof StaticCall) {
            return $this->resolveFromMethodCall($unwrappedNode);
        }

        if ($unwrappedNode instanceof FuncCall) {
            return $this->resolveFromNode($unwrappedNode->name);
        }

        $paramName = $this->nodeNameResolver->getName($unwrappedNode);
        if ($paramName !== null) {
            return $paramName;
        }

        if ($unwrappedNode instanceof String_) {
            return $unwrappedNode->value;
        }

        return null;
    }

    private function resolveFromMethodCall(MethodCall | NullsafeMethodCall | StaticCall $node): ?string
    {
        if ($node->name instanceof MethodCall) {
            return $this->resolveFromMethodCall($node->name);
        }

        $methodName = $this->nodeNameResolver->getName($node->name);
        if (! is_string($methodName)) {
            return null;
        }

        return $methodName;
    }

    private function unwrapNode(Node $node): ?Node
    {
        if ($node instanceof Arg) {
            return $node->value;
        }

        if ($node instanceof Cast) {
            return $node->expr;
        }

        if ($node instanceof Ternary) {
            return $node->if;
        }

        return $node;
    }
}
