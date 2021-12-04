<?php

declare(strict_types=1);

namespace Rector\Transform\Rector\Class_;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Rector\Php80\NodeAnalyzer\PhpAttributeAnalyzer;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://wiki.php.net/rfc/deprecate_dynamic_properties
 *
 * @see \Rector\Tests\Transform\Rector\Class_\RemoveAllowDynamicPropertiesAttributeRector\RemoveAllowDynamicPropertiesAttributeRectorTest
 */
final class RemoveAllowDynamicPropertiesAttributeRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var string
     */
    private const ATTRIBUTE = 'AllowDynamicProperties';

    public const TRANSFORM_ON_NAMESPACES = 'transform_on_namespaces';

    /**
     * @var array<array-key, string>
     */
    private array $transformOnNamespaces = [];

    public function __construct(
        private PhpAttributeAnalyzer $phpAttributeAnalyzer,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove the `AllowDynamicProperties` attribute from all classes', [
            new CodeSample(
                <<<'CODE_SAMPLE'
#[AllowDynamicProperties]
class SomeObject {
    public string $someProperty = 'hello world';
}
CODE_SAMPLE,

                <<<'CODE_SAMPLE'
class SomeObject {
    public string $someProperty = 'hello world';
}
CODE_SAMPLE
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

    public function configure(array $configuration): void
    {
        $this->transformOnNamespaces = $configuration[self::TRANSFORM_ON_NAMESPACES] ?? [];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->shouldRemove($node)) {
            return $this->removeAllowDynamicPropertiesAttribute($node);
        }

        return null;
    }

    private function removeAllowDynamicPropertiesAttribute(Class_ $class): Class_
    {
        $newAttrGroups = [];
        foreach ($class->attrGroups as $attrGroup) {
            $newAttrs = [];
            foreach ($attrGroup->attrs as $attribute) {
                if (! $this->nodeNameResolver->isName($attribute, self::ATTRIBUTE)) {
                    $newAttrs[] = $attribute;
                }
            }
            $attrGroup->attrs = $newAttrs;
            if (count($attrGroup->attrs) !== 0) {
                $newAttrGroups[] = $attrGroup;
            }
        }
        $class->attrGroups = $newAttrGroups;
        return $class;
    }

    private function shouldRemove(Class_ $class): bool
    {
        if (count($this->transformOnNamespaces) !== 0) {
            $className = (string) $this->nodeNameResolver->getName($class);
            foreach ($this->transformOnNamespaces as $transformOnNamespace) {
                if (! $this->nodeNameResolver->isStringName($className, $transformOnNamespace)) {
                    return false;
                }
            }
        }

        return $this->phpAttributeAnalyzer->hasPhpAttribute($class, self::ATTRIBUTE);
    }
}
