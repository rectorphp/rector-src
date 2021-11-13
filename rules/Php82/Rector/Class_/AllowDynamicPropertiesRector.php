<?php

namespace Rector\Php82\Rector\Class_;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\Core\Rector\AbstractRector;
use Rector\PhpAttribute\Printer\PhpAttributeGroupFactory;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

// TODO: consider should this implement MinPhpVersionInterface - won't hurt old PHP versions so I think no.
class AllowDynamicPropertiesRector extends AbstractRector
{
    /**
     * @var string
     */
    private const ATTRIBUTE = 'AllowDynamicProperties';

    public function __construct(
        private PhpAttributeGroupFactory $phpAttributeGroupFactory,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add the `AllowDynamicProperties` attribute to all classes', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeObject {
    public string $someProperty = 'hello world';
}
CODE_SAMPLE

                ,
                <<<'CODE_SAMPLE'
#[AllowDynamicProperties]
class SomeObject {
    public string $someProperty = 'hello world';
}
CODE_SAMPLE
            ),
        ]);
    }


    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    public function refactor(Node $node)
    {
        $skipAttribute = false;

        /**
         * @var \PhpParser\Node\AttributeGroup $attrGroup
         */
        foreach ($node->attrGroups as $attrGroup) {
            /**
             * @var \PhpParser\Node\Attribute $attribute
             */
            foreach ($attrGroup->attrs as $key => $attribute) {
                if ($this->isName($attribute->name, 'AllowDynamicProperties')) {
                    $skipAttribute = true;
                    break 2;
                }
            }
        }

        if (!$skipAttribute) {
            $node = $this->addAllowDynamicPropertiesAttribute($node);
        }

        return $node;
    }

    private function addAllowDynamicPropertiesAttribute(Node $node): Node
    {
        $attributeGroup = $this->phpAttributeGroupFactory->createFromClass(self::ATTRIBUTE);
        $node->attrGroups[] = $attributeGroup;

        return $node;
    }
}
