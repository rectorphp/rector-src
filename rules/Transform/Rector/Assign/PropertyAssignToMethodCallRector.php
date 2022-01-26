<?php

declare(strict_types=1);

namespace Rector\Transform\Rector\Assign;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Rector\Transform\ValueObject\PropertyAssignToMethodCall;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\Tests\Transform\Rector\Assign\PropertyAssignToMethodCallRector\PropertyAssignToMethodCallRectorTest
 */
final class PropertyAssignToMethodCallRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @deprecated
     * @var string
     */
    public const PROPERTY_ASSIGNS_TO_METHODS_CALLS = 'property_assigns_to_methods_calls';

    /**
     * @var PropertyAssignToMethodCall[]
     */
    private array $propertyAssignsToMethodCalls = [];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Turns property assign of specific type and property name to method call', [
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
$someObject = new SomeClass;
$someObject->oldProperty = false;
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
$someObject = new SomeClass;
$someObject->newMethodCall(false);
CODE_SAMPLE
                ,
                [new PropertyAssignToMethodCall('SomeClass', 'oldProperty', 'newMethodCall')]
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Assign::class];
    }

    /**
     * @param Assign $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $node->var instanceof PropertyFetch) {
            return null;
        }

        $propertyFetchNode = $node->var;

        /** @var Variable $propertyNode */
        $propertyNode = $propertyFetchNode->var;

        foreach ($this->propertyAssignsToMethodCalls as $propertyAssignToMethodCall) {
            if (! $this->isObjectType($propertyFetchNode->var, $propertyAssignToMethodCall->getObjectType())) {
                continue;
            }

            if (! $this->isName($propertyFetchNode, $propertyAssignToMethodCall->getOldPropertyName())) {
                continue;
            }

            return $this->nodeFactory->createMethodCall(
                $propertyNode,
                $propertyAssignToMethodCall->getNewMethodName(),
                [$node->expr]
            );
        }

        return $node;
    }

    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration): void
    {
        $propertyAssignsToMethodCalls = $configuration[self::PROPERTY_ASSIGNS_TO_METHODS_CALLS] ?? $configuration;
        Assert::allIsAOf($propertyAssignsToMethodCalls, PropertyAssignToMethodCall::class);
        $this->propertyAssignsToMethodCalls = $propertyAssignsToMethodCalls;
    }
}
