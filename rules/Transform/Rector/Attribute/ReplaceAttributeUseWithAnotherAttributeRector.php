<?php

declare(strict_types=1);

namespace Rector\Transform\Rector\Attribute;

use PhpParser\Node;
use PhpParser\Node\Attribute;
use PhpParser\Node\Name\FullyQualified;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Rector\AbstractRector;
use Rector\Transform\ValueObject\ReplaceAttribute;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\Tests\Transform\Rector\Attribute\ReplaceAttributeUseWithAnotherAttributeRector\ReplaceAttributeUseWithAnotherAttributeRectorTest
 */
class ReplaceAttributeUseWithAnotherAttributeRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var ReplaceAttribute[]
     */
    private array $configuration;

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Replaces one attribute with another', [
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
class Foobar
{
    #[\Foobar('parameter')]
    public $foobar;
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
class Foobar
{
    #[\Barfoo('parameter')]
    public $foobar;
}
CODE_SAMPLE
                ,
                [new ReplaceAttribute(new FullyQualified('Foobar'), new FullyQualified('Barfoo'))]
            ),
        ]);
    }

    public function getNodeTypes(): array
    {
        return [Attribute::class];
    }

    /**
     * @param Node\Attribute $node
     */
    public function refactor(Node $node): ?Attribute
    {
        foreach ($this->configuration as $item) {
            if ($item->original->toString() === $node->name->toString()) {
                return new Attribute($item->replacement, $node->args, []);
            }
        }

        return null;
    }

    public function configure(array $configuration): void
    {
        Assert::allIsInstanceOf($configuration, ReplaceAttribute::class);
        $this->configuration = $configuration;
    }
}
