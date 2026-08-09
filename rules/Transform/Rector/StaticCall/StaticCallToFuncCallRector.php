<?php

declare(strict_types=1);

namespace Rector\Transform\Rector\StaticCall;

use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name\FullyQualified;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Rector\AbstractRector;
use Rector\Transform\ValueObject\StaticCallToFuncCall;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @extends AbstractRector<StaticCall>
 * @implements ConfigurableRectorInterface<StaticCall>
 * @see \Rector\Tests\Transform\Rector\StaticCall\StaticCallToFuncCallRector\StaticCallToFuncCallRectorTest
 */
final class StaticCallToFuncCallRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var StaticCallToFuncCall[]
     */
    private array $staticCallsToFunctions = [];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Turn static call to function call', [
            new ConfiguredCodeSample(
                'OldClass::oldMethod("args");',
                'new_function("args");',
                [new StaticCallToFuncCall('OldClass', 'oldMethod', 'new_function')]
            ),
        ]);
    }

    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    public function refactor(Node $node): ?Node
    {
        foreach ($this->staticCallsToFunctions as $staticCallToFunction) {
            if (! $this->isName($node->name, $staticCallToFunction->getMethod())) {
                continue;
            }

            if (! $this->isObjectType($node->class, $staticCallToFunction->getObjectType())) {
                continue;
            }

            return new FuncCall(new FullyQualified($staticCallToFunction->getFunction()), $node->args);
        }

        return null;
    }

    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration): void
    {
        Assert::allIsAOf($configuration, StaticCallToFuncCall::class);

        $this->staticCallsToFunctions = $configuration;
    }
}
