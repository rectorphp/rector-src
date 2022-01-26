<?php

declare(strict_types=1);

namespace Rector\Removing\Rector\Class_;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\Tests\Removing\Rector\Class_\RemoveInterfacesRector\RemoveInterfacesRectorTest
 */
final class RemoveInterfacesRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @deprecated
     * @var string
     */
    public const INTERFACES_TO_REMOVE = 'interfaces_to_remove';

    /**
     * @var string[]
     */
    private array $interfacesToRemove = [];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Removes interfaces usage from class.', [
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
class SomeClass implements SomeInterface
{
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
class SomeClass
{
}
CODE_SAMPLE
                ,
                ['SomeInterface']
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node->implements === []) {
            return null;
        }

        foreach ($node->implements as $key => $implement) {
            if ($this->isNames($implement, $this->interfacesToRemove)) {
                unset($node->implements[$key]);
            }
        }

        return $node;
    }

    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration): void
    {
        $interfacesToRemove = $configuration[self::INTERFACES_TO_REMOVE] ?? $configuration;
        Assert::allString($interfacesToRemove);

        /** @var string[] $interfacesToRemove */
        $this->interfacesToRemove = $interfacesToRemove;
    }
}
