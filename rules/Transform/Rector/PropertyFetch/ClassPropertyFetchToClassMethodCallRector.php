<?php

declare(strict_types=1);

namespace Rector\Transform\Rector\PropertyFetch;

use PhpParser\Node;
use PhpParser\Node\Expr\PropertyFetch;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Rector\Transform\ValueObject\ClassPropertyFetchToClassMethodCall;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\Tests\Transform\Rector\PropertyFetch\ClassPropertyFetchToClassMethodCallRector\ClassPropertyFetchToClassMethodCallRectorTest
 */
final class ClassPropertyFetchToClassMethodCallRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var string
     */
    public const CLASS_PROPERTIES_TO_CLASS_METHOD_CALLS = 'class-properties-to-class-method-calls';

    /**
     * @var ClassPropertyFetchToClassMethodCall[]
     */
    private array $classPropertiesToClassMethodCalls = [];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Turns property fetch "$this->propertySomething" to method call "$this->something()"',
            [
                new ConfiguredCodeSample(
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        $this->something;
    }
}
CODE_SAMPLE

                    ,
                    <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        $this->somethingElse();
    }
}
CODE_SAMPLE
,
                    [
                        self::CLASS_PROPERTIES_TO_CLASS_METHOD_CALLS => [
                            new ClassPropertyFetchToClassMethodCall('SomeObject', 'property', 'someMethod'),
                        ],
                    ]
                ),

            ]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [PropertyFetch::class];
    }

    /**
     * @param PropertyFetch $node
     */
    public function refactor(Node $node): ?Node
    {
        foreach ($this->classPropertiesToClassMethodCalls as $classPropertyToClassMethodCall) {
            if (! $this->isObjectType($node->var, $classPropertyToClassMethodCall->getObjecType())) {
                continue;
            }

            if (! $this->isName($node->name, $classPropertyToClassMethodCall->getProperty())) {
                continue;
            }

            return $this->nodeFactory->createMethodCall($node->var, $classPropertyToClassMethodCall->getMethod());
        }

        return null;
    }

    /**
     * @param array<string, ClassPropertyFetchToClassMethodCall[]> $configuration
     */
    public function configure(array $configuration): void
    {
        $classPropertiesToClassMethodCalls = $configuration[self::CLASS_PROPERTIES_TO_CLASS_METHOD_CALLS] ?? [];
        Assert::allIsInstanceOf($classPropertiesToClassMethodCalls, ClassPropertyFetchToClassMethodCall::class);
        $this->classPropertiesToClassMethodCalls = $classPropertiesToClassMethodCalls;
    }
}
