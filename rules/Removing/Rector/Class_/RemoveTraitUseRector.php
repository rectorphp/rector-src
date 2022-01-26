<?php

declare(strict_types=1);

namespace Rector\Removing\Rector\Class_;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Trait_;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\Tests\Removing\Rector\Class_\RemoveTraitUseRector\RemoveTraitUseRectorTest
 */
final class RemoveTraitUseRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @deprecated
     * @var string
     */
    public const TRAITS_TO_REMOVE = 'traits_to_remove';

    private bool $classHasChanged = false;

    /**
     * @var string[]
     */
    private array $traitsToRemove = [];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove specific traits from code', [
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
    use SomeTrait;
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
class SomeClass
{
}
CODE_SAMPLE
,
                ['TraitNameToRemove']
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Class_::class, Trait_::class];
    }

    /**
     * @param Class_|Trait_ $node
     */
    public function refactor(Node $node): ?Node
    {
        $this->classHasChanged = false;

        foreach ($node->getTraitUses() as $traitUse) {
            foreach ($traitUse->traits as $trait) {
                if (! $this->isNames($trait, $this->traitsToRemove)) {
                    continue;
                }

                $this->removeNode($traitUse);
                $this->classHasChanged = true;
            }
        }

        // invoke re-print
        if ($this->classHasChanged) {
            $node->setAttribute(AttributeKey::ORIGINAL_NODE, null);
            return $node;
        }

        return null;
    }

    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration): void
    {
        $traitsToRemove = $configuration[self::TRAITS_TO_REMOVE] ?? $configuration;
        Assert::allString($traitsToRemove);

        /** @var string[] $traitsToRemove */
        $this->traitsToRemove = $traitsToRemove;
    }
}
