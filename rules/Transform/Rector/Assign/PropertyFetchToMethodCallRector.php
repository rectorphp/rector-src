<?php

declare(strict_types=1);

namespace Rector\Transform\Rector\Assign;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\Rector\AbstractRector;
use Rector\Transform\ValueObject\PropertyFetchToMethodCall;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\Tests\Transform\Rector\Assign\PropertyFetchToMethodCallRector\PropertyFetchToMethodCallRectorTest
 */
final class PropertyFetchToMethodCallRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var string
     */
    public const PROPERTIES_TO_METHOD_CALLS = 'properties_to_method_calls';

    /**
     * @var PropertyFetchToMethodCall[]
     */
    private array $propertiesToMethodCalls = [];

    public function getRuleDefinition(): RuleDefinition
    {
        $firstConfiguration = [
            self::PROPERTIES_TO_METHOD_CALLS => [
                new PropertyFetchToMethodCall('SomeObject', 'property', 'getProperty', 'setProperty'),
            ],
        ];

        $secondConfiguration = [
            self::PROPERTIES_TO_METHOD_CALLS => [
                new PropertyFetchToMethodCall('SomeObject', 'property', 'getConfig', null, ['someArg']),
            ],
        ];

        return new RuleDefinition('Replaces properties assign calls be defined methods.', [
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
$result = $object->property;
$object->property = $value;
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
$result = $object->getProperty();
$object->setProperty($value);
CODE_SAMPLE
                ,
                $firstConfiguration
            ),
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
$result = $object->property;
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
$result = $object->getProperty('someArg');
CODE_SAMPLE
                ,
                $secondConfiguration
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [PropertyFetch::class, Assign::class];
    }

    /**
     * @param Assign|PropertyFetch $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node instanceof PropertyFetch) {
            return $this->processPropertyFetch($node);
        }

        if ($node->var instanceof PropertyFetch) {
            return $this->processSetter($node);
        }

        if ($node->expr instanceof PropertyFetch) {
            return $this->processGetter($node);
        }

        return null;
    }

    /**
     * @param array<string, PropertyFetchToMethodCall[]> $configuration
     */
    public function configure(array $configuration): void
    {
        $propertiesToMethodCalls = $configuration[self::PROPERTIES_TO_METHOD_CALLS] ?? [];
        Assert::allIsInstanceOf($propertiesToMethodCalls, PropertyFetchToMethodCall::class);
        $this->propertiesToMethodCalls = $propertiesToMethodCalls;
    }

    private function processSetter(Assign $assign): ?Node
    {
        /** @var PropertyFetch $propertyFetchNode */
        $propertyFetchNode = $assign->var;

        $propertyToMethodCall = $this->matchPropertyFetchCandidate($propertyFetchNode);
        if (! $propertyToMethodCall instanceof PropertyFetchToMethodCall) {
            return null;
        }

        if ($propertyToMethodCall->getNewSetMethod() === null) {
            throw new ShouldNotHappenException();
        }

        $args = $this->nodeFactory->createArgs([$assign->expr]);

        /** @var Variable $variable */
        $variable = $propertyFetchNode->var;

        return $this->nodeFactory->createMethodCall($variable, $propertyToMethodCall->getNewSetMethod(), $args);
    }

    private function processGetter(Assign $assign): Assign
    {
        /** @var PropertyFetch $propertyFetchNode */
        $propertyFetchNode = $assign->expr;

        $propertyFetchNodeToMethodCall = $this->transformPropertyFetchToMethodCall($propertyFetchNode);

        if (! $propertyFetchNodeToMethodCall instanceof MethodCall) {
            return $assign;
        }

        $assign->expr = $propertyFetchNodeToMethodCall;

        return $assign;
    }

    private function matchPropertyFetchCandidate(PropertyFetch $propertyFetch): ?PropertyFetchToMethodCall
    {
        foreach ($this->propertiesToMethodCalls as $propertyToMethodCall) {
            if (! $this->isObjectType($propertyFetch->var, $propertyToMethodCall->getOldObjectType())) {
                continue;
            }

            if (! $this->isName($propertyFetch, $propertyToMethodCall->getOldProperty())) {
                continue;
            }

            return $propertyToMethodCall;
        }

        return null;
    }

    private function processPropertyFetch(PropertyFetch $propertyFetch): ?MethodCall
    {
        return $this->transformPropertyFetchToMethodCall($propertyFetch);
    }

    private function transformPropertyFetchToMethodCall(PropertyFetch $propertyFetch): ?MethodCall
    {
        $propertyToMethodCall = $this->matchPropertyFetchCandidate($propertyFetch);
        if (! $propertyToMethodCall instanceof PropertyFetchToMethodCall) {
            return null;
        }

        if ($propertyToMethodCall->getNewGetMethod() === '') {
            return null;
        }

        $args = [];

        if ($propertyToMethodCall->getNewGetArguments() !== []) {
            $args = $this->nodeFactory->createArgs($propertyToMethodCall->getNewGetArguments());
        }

        return $this->nodeFactory->createMethodCall(
            $propertyFetch->var,
            $propertyToMethodCall->getNewGetMethod(),
            $args
        );
    }
}
