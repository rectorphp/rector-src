<?php declare(strict_types=1);

namespace Rector\NodeAnalyzer;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use Rector\BetterReflection\Reflector\MethodReflector;
use Rector\BetterReflection\Reflector\SmartClassReflector;
use Rector\Node\Attribute;
use Rector\NodeTraverserQueue\BetterNodeFinder;
use ReflectionMethod;

final class MethodCallAnalyzer
{
    /**
     * @var SmartClassReflector
     */
    private $smartClassReflector;

    /**
     * @var BetterNodeFinder
     */
    private $betterNodeFinder;

    /**
     * @var string[][]
     */
    private $publicMethodNamesForType = [];

    /**
     * @var MethodReflector
     */
    private $methodReflector;

    public function __construct(
        SmartClassReflector $smartClassReflector,
        BetterNodeFinder $betterNodeFinder,
        MethodReflector $methodReflector
    ) {
        $this->smartClassReflector = $smartClassReflector;
        $this->betterNodeFinder = $betterNodeFinder;
        $this->methodReflector = $methodReflector;
    }

    /**
     * @param string[] $types
     * @param string[] $methods
     */
    public function isTypesAndMethods(Node $node, array $types, array $methods): bool
    {
        if (! $node instanceof MethodCall) {
            return false;
        }

        $variableTypes = $this->resolveVariableTypes($node);
        if (! (bool) array_intersect($types, $variableTypes)) {
            return false;
        }

        return $this->isMethods($node, $methods);
    }

    /**
     * Checks "$this->classOfSpecificType->specificMethodName()"
     *
     * @param string[] $methods
     */
    public function isTypeAndMethods(Node $node, string $type, array $methods): bool
    {
        if (! $this->isType($node, $type)) {
            return false;
        }

        /** @var MethodCall $node */
        return in_array($node->name->toString(), $methods, true);
    }

    /**
     * Checks "$this->classOfSpecificType->specificMethodName()"
     */
    public function isTypeAndMethod(Node $node, string $type, string $method): bool
    {
        if (! $this->isType($node, $type)) {
            return false;
        }

        /** @var MethodCall $node */
        return $node->name->toString() === $method;
    }

    /**
     * Checks "$this->specificNameMethod()"
     */
    public function isMethod(Node $node, string $methodName): bool
    {
        if (! $node instanceof MethodCall) {
            return false;
        }

        if (! $node->name instanceof Identifier) {
            return false;
        }

        return $node->name->name === $methodName;
    }

    /**
     * @param string[] $methods
     */
    public function isMethods(Node $node, array $methods): bool
    {
        if (! $node instanceof MethodCall) {
            return false;
        }

        if (! $node->name instanceof Identifier) {
            return false;
        }

        return in_array($node->name->name, $methods, true);
    }

    /**
     * Checks "$this->methodCall()"
     */
    public function isType(Node $node, string $type): bool
    {
        if (! $node instanceof MethodCall) {
            return false;
        }

        $variableTypes = $this->resolveVariableTypes($node);

        return in_array($type, $variableTypes, true);
    }

    /**
     * @param string[] $types
     * @return string[]
     */
    public function matchTypes(Node $node, array $types): ?array
    {
        if (! $node instanceof MethodCall) {
            return null;
        }

        $nodeTypes = $node->var->getAttribute(Attribute::TYPES);

        return array_intersect($nodeTypes, $types) ? $nodeTypes : null;
    }

    public function isTypeAndMagic(Node $node, string $type): bool
    {
        if (! $this->isType($node, $type)) {
            return false;
        }

        /** @var MethodCall $node */
        $nodeMethodName = $node->name->name;

        $publicMethodNames = $this->getPublicMethodNamesForType($type);

        return ! in_array($nodeMethodName, $publicMethodNames, true);
    }

    /**
     * @return string[]
     */
    private function getPublicMethodNamesForType(string $type): array
    {
        if (isset($this->publicMethodNamesForType[$type])) {
            return $this->publicMethodNamesForType[$type];
        }

        $classReflection = $this->smartClassReflector->reflect($type);
        $publicMethods = $classReflection->getMethods(ReflectionMethod::IS_PUBLIC);

        return $this->publicMethodNamesForType[$type] = array_keys($publicMethods);
    }

    /**
     * @return string[]
     */
    private function resolveVariableTypes(MethodCall $methodCallNode): array
    {
        $methodCallNode->getAttribute(Attribute::RETURN_TYPES);

        $parentNode = $methodCallNode->getAttribute(Attribute::PARENT_NODE);

        if ($parentNode instanceof MethodCall && $parentNode->var instanceof MethodCall) {
            // resolve return type type
            // @todo: consider Attribute::RETURN_TYPES for MethodCall and StaticCall types

            $nodeVarTypes = $parentNode->var->var->getAttribute(Attribute::TYPES);
            $nodeVarType = array_shift($nodeVarTypes);

            $methodName = $parentNode->var->name->toString(); // method
            $methodReturnType = $this->methodReflector->getMethodReturnType($nodeVarType, $methodName);

            if ($methodReturnType) {
                return [$methodReturnType];
            }
        }

        $node = $this->betterNodeFinder->findFirstInstanceOfAny(
            $methodCallNode,
            [PropertyFetch::class, Variable::class]
        );

        $nodeTypes = (array) $node->getAttribute(Attribute::TYPES);
        if ($nodeTypes) {
            return $nodeTypes;
        }

        return [];
    }
}
