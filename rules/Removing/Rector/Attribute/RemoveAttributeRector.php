<?php

declare(strict_types=1);

namespace Rector\Removing\Rector\Attribute;

use PhpParser\Node;
use PhpParser\Node\AttributeGroup;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Property;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Rector\AbstractRector;
use Rector\Removing\ValueObject\RemoveAttribute;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\Tests\Removing\Rector\Attribute\RemoveAttributeRector\RemoveAttributeRectorTest
 */
final class RemoveAttributeRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var array<RemoveAttribute>
     */
    private array $removeAttributes = [];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Removes attributes (from specific node types)', [
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
#[Foo]
class SomeClass
{
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
#[Foo]
class SomeClass
{
}
CODE_SAMPLE
                ,
                [new RemoveAttribute('Foo')]
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Node::class];
    }

    public function refactor(Node $node): ?Node
    {
        if (! in_array('attrGroups', $node->getSubNodeNames(), true)) {
            return null;
        }

        if (! isset($node->attrGroups) || $node->attrGroups === null || $node->attrGroups === []) {
            return null;
        }

        foreach ($node->attrGroups as $attrGroup) {
            if (! $attrGroup instanceof AttributeGroup) {
                return null;
            }
        }

        $nodeTypes = [$node::class];
        if ($node instanceof Param && $node->isPromoted()) {
            // An attribute removed from a parameter or property must be removed from a promoted property because an
            // attribute on a promoted property applies to the constructor parameter and the object property.
            $nodeTypes[] = Property::class;
        }

        $relevantRemoveAttributes = [];
        foreach ($this->removeAttributes as $removeAttribute) {
            if ($removeAttribute->getNodeTypes() === [] || array_intersect(
                $nodeTypes,
                $removeAttribute->getNodeTypes()
            ) !== []) {
                $relevantRemoveAttributes[] = $removeAttribute;
            }
        }

        if ($relevantRemoveAttributes === []) {
            return null;
        }

        $hasChanged = false;

        /** @var array<AttributeGroup> $attrGroups */
        $attrGroups = $node->attrGroups;

        foreach ($attrGroups as $attrGroupKey => $attrGroup) {
            foreach ($attrGroup->attrs as $key => $attribute) {
                foreach ($relevantRemoveAttributes as $removeAttribute) {
                    if (! $this->isName($attribute, $removeAttribute->getClass())) {
                        continue;
                    }

                    unset($attrGroup->attrs[$key]);

                    $hasChanged = true;
                }
            }

            if ($attrGroup->attrs === []) {
                unset($node->attrGroups[$attrGroupKey]);

                $hasChanged = true;
            }
        }

        if ($hasChanged) {
            return $node;
        }

        return null;
    }

    public function configure(array $configuration): void
    {
        Assert::allIsInstanceOf($configuration, RemoveAttribute::class);

        $this->removeAttributes = $configuration;
    }
}
