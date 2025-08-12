<?php

declare(strict_types=1);

namespace Rector\Removing\Rector\Attribute;

use PhpParser\Node;
use PhpParser\Node\AttributeGroup;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Param;
use PhpParser\Node\PropertyHook;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Const_;
use PhpParser\Node\Stmt\EnumCase;
use PhpParser\Node\Stmt\Function_;
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
        return [
            ArrowFunction::class,
            ClassConst::class,
            ClassLike::class,
            ClassMethod::class,
            Closure::class,
            Const_::class,
            EnumCase::class,
            Function_::class,
            Param::class,
            Property::class,
            PropertyHook::class,
        ];
    }

    /**
     * @param ArrowFunction|ClassConst|ClassLike|ClassMethod|Closure|Const_|EnumCase|Function_|Param|Property|PropertyHook $node
     */
    public function refactor(Node $node): ?Node
    {
        $relevantRemoveAttributes = [];
        foreach ($this->removeAttributes as $removeAttribute) {
            if ($removeAttribute->getNodeTypes() === [] || in_array(
                $node::class,
                $removeAttribute->getNodeTypes(),
                true
            )) {
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
