<?php

namespace Rector\Php82\Rector\Class_;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Type\ObjectType;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersion;
use Rector\PhpAttribute\Printer\PhpAttributeGroupFactory;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://github.com/php/php-src/pull/7571
 *
 * @see \Rector\Tests\Php82\Rector\Class_\AllowDynamicPropertiesRector\AllowDynamicPropertiesRectorTest
 */
class AllowDynamicPropertiesRector extends AbstractRector implements MinPhpVersionInterface
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
        assert($node instanceof Class_);

        foreach ($node->attrGroups as $attrGroup) {
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

    private function addAllowDynamicPropertiesAttribute(Class_ $node): Node
    {
        $attributeGroup = $this->phpAttributeGroupFactory->createFromClass(self::ATTRIBUTE);
        $node->attrGroups[] = $attributeGroup;

        return $node;
    }

    public function provideMinPhpVersion(): int
    {
        // TODO: Consider what actual version to target...
        return PhpVersion::PHP_81;
    }
}
