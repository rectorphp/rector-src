<?php

namespace Rector\Transform\Rector\Class_;

use PhpParser\Node;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Reflection\ReflectionProvider;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\FamilyTree\Reflection\FamilyRelationsAnalyzer;
use Rector\Php80\NodeAnalyzer\PhpAttributeAnalyzer;
use Rector\PhpAttribute\Printer\PhpAttributeGroupFactory;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://wiki.php.net/rfc/deprecate_dynamic_properties
 *
 * @see \Rector\Tests\Transform\Rector\Class_\AddAllowDynamicPropertiesAttributeRector\AddAllowDynamicPropertiesAttributeRectorTest
 */
final class AddAllowDynamicPropertiesAttributeRector extends AbstractRector implements MinPhpVersionInterface
{
    /**
     * @var string
     */
    private const ATTRIBUTE = 'AllowDynamicProperties';

    public function __construct(
        private FamilyRelationsAnalyzer $familyRelationsAnalyzer,
        private PhpAttributeAnalyzer $phpAttributeAnalyzer,
        private PhpAttributeGroupFactory $phpAttributeGroupFactory,
        private ReflectionProvider $reflectionProvider,
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

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if (
            $this->isDescendantOfStdclass($node) ||
            $this->hasNeededAttributeAlready($node) ||
            $this->hasMagicSetMethod($node)
        ) {
            return null;
        }

        return $this->addAllowDynamicPropertiesAttribute($node);
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::DEPRECATE_DYNAMIC_PROPERTIES;
    }

    private function isDescendantOfStdclass(Class_ $node): bool
    {
        if (!$node->extends instanceof FullyQualified) {
            return false;
        }

        $ancestorClassNames = $this->familyRelationsAnalyzer->getClassLikeAncestorNames($node);
        return in_array('stdClass', $ancestorClassNames);
    }

    private function hasNeededAttributeAlready(Class_ $node): bool
    {
        $nodeHasAttribute = $this->phpAttributeAnalyzer->hasPhpAttribute($node, self::ATTRIBUTE);
        if ($nodeHasAttribute === true) {
            return true;
        }

        if (!$node->extends instanceof FullyQualified) {
            return false;
        }

        // TODO: recursive check for if ancestors applied the attribute
        //$parentNode = $this->getParentClassNode($node);
        return false;
    }

    private function hasMagicSetMethod(Class_ $node): bool
    {
        $classReflection = $this->reflectionProvider->getClass($node->namespacedName);
        if ($classReflection->hasMethod('__set')) {
            return true;
        }

        return false;
    }

    private function addAllowDynamicPropertiesAttribute(Class_ $node): Class_
    {
        $attributeGroup = $this->phpAttributeGroupFactory->createFromClass(self::ATTRIBUTE);
        $node->attrGroups[] = $attributeGroup;

        return $node;
    }
}
