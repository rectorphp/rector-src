<?php

declare(strict_types=1);

namespace Rector\CodeQuality\Rector\PropertyFetch;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Reflection\ResolvedMethodReflection;
use PHPStan\Type\ObjectType;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\MethodName;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://github.com/symplify/phpstan-rules/blob/main/docs/rules_overview.md#explicitmethodcallovermagicgetsetrule
 *
 * @inspired by \Rector\Transform\Rector\Assign\GetAndSetToMethodCallRector
 * @phpstan-rule https://github.com/symplify/phpstan-rules/blob/main/src/Rules/Explicit/ExplicitMethodCallOverMagicGetSetRule.php
 *
 * @see \Rector\Tests\CodeQuality\Rector\PropertyFetch\ExplicitMethodCallOverMagicGetSetRector\ExplicitMethodCallOverMagicGetSetRectorTest
 */
final class ExplicitMethodCallOverMagicGetSetRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace magic property fetch using __get() and __set() with existing method get*()/set*() calls',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
class MagicCallsObject
{
    // adds magic __get() and __set() methods
    use \Nette\SmartObject;

    private $name;

    public function getName()
    {
        return $this->name;
    }
}

class SomeClass
{
    public function run(MagicObject $magicObject)
    {
        return $magicObject->name;
    }
}
CODE_SAMPLE

                    ,
                    <<<'CODE_SAMPLE'
class MagicCallsObject
{
    // adds magic __get() and __set() methods
    use \Nette\SmartObject;

    private $name;

    public function getName()
    {
        return $this->name;
    }
}

class SomeClass
{
    public function run(MagicObject $magicObject)
    {
        return $magicObject->getName();
    }
}
CODE_SAMPLE
                ),

            ]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [PropertyFetch::class, Assign::class];
    }

    /**
     * @param PropertyFetch|Assign $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node instanceof Assign) {
            if ($node->var instanceof PropertyFetch) {
                return $this->refactorMagicSet($node->expr, $node->var);
            }

            return null;
        }

        if ($this->shouldSkipPropertyFetch($node)) {
            return null;
        }

        return $this->refactorPropertyFetch($node);
    }

    /**
     * @return string[]
     */
    public function resolvePossibleGetMethodNames(string $propertyName): array
    {
        return ['get' . ucfirst($propertyName), 'has' . ucfirst($propertyName), 'is' . ucfirst($propertyName)];
    }

    private function shouldSkipPropertyFetch(PropertyFetch $propertyFetch): bool
    {
        $parent = $propertyFetch->getAttribute(AttributeKey::PARENT_NODE);
        if (! $parent instanceof Assign) {
            return false;
        }

        return $parent->var === $propertyFetch;
    }

    private function refactorPropertyFetch(PropertyFetch $propertyFetch): MethodCall|null
    {
        $callerType = $this->getType($propertyFetch->var);
        if (! $callerType instanceof ObjectType) {
            return null;
        }

        // has magic methods?
        if (! $callerType->hasMethod(MethodName::__GET)->yes()) {
            return null;
        }

        $propertyName = $this->getName($propertyFetch->name);
        if ($propertyName === null) {
            return null;
        }

        $possibleGetterMethodNames = $this->resolvePossibleGetMethodNames($propertyName);

        foreach ($possibleGetterMethodNames as $possibleGetterMethodName) {
            if (! $callerType->hasMethod($possibleGetterMethodName)->yes()) {
                continue;
            }

            return $this->nodeFactory->createMethodCall($propertyFetch->var, $possibleGetterMethodName);
        }

        return null;
    }

    private function refactorMagicSet(Expr $expr, PropertyFetch $propertyFetch): MethodCall|null
    {
        $propertyCallerType = $this->getType($propertyFetch->var);
        if (! $propertyCallerType instanceof ObjectType) {
            return null;
        }

        if (! $propertyCallerType->hasMethod(MethodName::__SET)->yes()) {
            return null;
        }

        $propertyName = $this->getName($propertyFetch->name);
        if ($propertyName === null) {
            return null;
        }

        $setterMethodName = 'set' . ucfirst($propertyName);
        if (! $propertyCallerType->hasMethod($setterMethodName)->yes()) {
            return null;
        }

        if ($this->hasNoParamOrVariadic($propertyCallerType, $propertyFetch, $setterMethodName)) {
            return null;
        }

        return $this->nodeFactory->createMethodCall($propertyFetch->var, $setterMethodName, [$expr]);
    }

    private function hasNoParamOrVariadic(
        ObjectType $objectType,
        PropertyFetch $propertyFetch,
        string $setterMethodName
    ): bool {
        $scope = $propertyFetch->getAttribute(AttributeKey::SCOPE);
        if (! $scope instanceof Scope) {
            return false;
        }

        $methodReflection = $objectType->getMethod($setterMethodName, $scope);

        if (! $methodReflection instanceof ResolvedMethodReflection) {
            return false;
        }

        $parametersAcceptor = ParametersAcceptorSelector::selectSingle($methodReflection->getVariants());
        $parameters = $parametersAcceptor->getParameters();
        if (count($parameters) !== 1) {
            return true;
        }

        return $parameters[0]->isVariadic();
    }
}
