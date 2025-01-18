<?php

declare(strict_types=1);

namespace Rector\CodingStyle\Rector\FuncCall;

use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PHPStan\Reflection\ParameterReflectionWithPhpDocs;
use PHPStan\Reflection\ReflectionProvider;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use const PHP_VERSION_ID;

final class AddNamedArgumentsRector extends AbstractRector
{
    public function __construct(private readonly ReflectionProvider $reflectionProvider)
    {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Convert all arguments to named arguments', [
            new CodeSample('$user->setPassword("123456");', '$user->changePassword(password: "123456");'),
        ]);
    }

    public function getNodeTypes(): array
    {
        return [FuncCall::class, StaticCall::class, MethodCall::class, New_::class];
    }

    public function refactor(Node $node): ?Node
    {
        if (PHP_VERSION_ID < 80000) {
            return null;
        }

        $parameters = $this->getParameters($node);
        $this->addNamesToArgs($node, $parameters);

        return $node;
    }

    /**
     * @param Node $node
     * @return ParameterReflectionWithPhpDocs[]
     */
    private function getParameters(Node $node): array
    {
        $parameters = [];

        if ($node instanceof New_) {
            $parameters = $this->getConstructorArgs($node);
        } elseif ($node instanceof MethodCall) {
            $parameters = $this->getMethodArgs($node);
        } elseif ($node instanceof StaticCall) {
            $parameters = $this->getStaticMethodArgs($node);
        } elseif ($node instanceof FuncCall) {
            $parameters = $this->getFuncArgs($node);
        }

        return $parameters;
    }

    /**
     * @return ParameterReflectionWithPhpDocs[]
     */
    private function getStaticMethodArgs(StaticCall $node): array
    {
        $namespaceAnswerer = $node->getAttribute(AttributeKey::SCOPE);

        if (! $node->class instanceof Name) {
            return [];
        }

        $className = $this->getName($node->class);
        if (! $this->reflectionProvider->hasClass($className)) {
            return [];
        }

        $classReflection = $this->reflectionProvider->getClass($className);
        if (! $classReflection->hasMethod($node->name->toString())) {
            return [];
        }

        $methodReflection = $classReflection->getMethod($node->name->toString(), $namespaceAnswerer);

        return $methodReflection->getOnlyVariant()->getParameters();
    }

    /**
     * @return ParameterReflectionWithPhpDocs[]
     */
    private function getMethodArgs(MethodCall $node): array
    {
        $namespaceAnswerer = $node->getAttribute(AttributeKey::SCOPE);

        $callerType = $this->nodeTypeResolver->getType($node->var);
        if (! $callerType->hasMethod($node->name->toString())) {
            return [];
        }

        $methodReflection = $callerType->getMethod($node->name->toString(), $namespaceAnswerer);

        return $methodReflection->getOnlyVariant()->getParameters();
    }

    private function resolveCalledName(Node $node): ?string
    {
        if ($node instanceof FuncCall && $node->name instanceof Name) {
            return (string) $node->name;
        }

        if ($node instanceof MethodCall && $node->name instanceof Identifier) {
            return (string) $node->name;
        }

        if ($node instanceof StaticCall && $node->name instanceof Identifier) {
            return (string) $node->name;
        }

        if ($node instanceof New_ && $node->class instanceof Name) {
            return (string) $node->class;
        }

        return null;
    }

    /**
     * @return ParameterReflectionWithPhpDocs[]
     */
    private function getConstructorArgs(New_ $node): array
    {
        $calledName = $this->resolveCalledName($node);
        if ($calledName === null) {
            return [];
        }

        if (! $this->reflectionProvider->hasClass($calledName)) {
            return [];
        }
        $classReflection = $this->reflectionProvider->getClass($calledName);

        if (! $classReflection->hasConstructor()) {
            return [];
        }

        $constructorReflection = $classReflection->getConstructor();

        return $constructorReflection->getOnlyVariant()->getParameters();
    }

    /**
     * @return ParameterReflectionWithPhpDocs[]
     */
    private function getFuncArgs(FuncCall $node): array
    {
        $namespaceAnswerer = $node->getAttribute(AttributeKey::SCOPE);

        $calledName = $this->resolveCalledName($node);
        if ($calledName === null) {
            return [];
        }

        if (! $this->reflectionProvider->hasFunction(new Name($calledName), $namespaceAnswerer)) {
            return [];
        }
        $reflection = $this->reflectionProvider->getFunction(new Name($calledName), $namespaceAnswerer);

        return $reflection->getOnlyVariant()->getParameters();
    }

    /**
     * @param ParameterReflectionWithPhpDocs[] $parameters
     */
    private function addNamesToArgs(Node $node, array $parameters): void
    {
        /** @var FuncCall|StaticCall|MethodCall|New_ $node */
        foreach ($node->args as $index => $arg) {
            if (! isset($parameters[$index])) {
                return;
            }
            $arg->name = new Identifier($parameters[$index]->getName());
        }
    }
}
