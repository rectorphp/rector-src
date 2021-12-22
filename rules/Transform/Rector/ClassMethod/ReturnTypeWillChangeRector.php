<?php

declare(strict_types=1);

namespace Rector\Transform\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Interface_;
use PHPStan\Type\ObjectType;
use Rector\Core\Contract\Rector\ConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\Php80\NodeAnalyzer\PhpAttributeAnalyzer;
use Rector\PhpAttribute\Printer\PhpAttributeGroupFactory;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class ReturnTypeWillChangeRector extends AbstractRector implements ConfigurableRectorInterface, MinPhpVersionInterface
{
    /**
     * @var class-string<ReturnTypeWillChange>
     */
    private const RETURN_TYPE_WILL_CHANGE_ATTRIBUTE = 'ReturnTypeWillChange';

    /**
     * @var array<string, string[]>
     */
    private array $classMethodsOfClass = [];

    public function __construct(
        private readonly PhpAttributeAnalyzer $phpAttributeAnalyzer,
        private readonly PhpAttributeGroupFactory $phpAttributeGroupFactory
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add #[\ReturnTypeWillChange] attribute to configured instanceof class with methods', [
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
class SomeClass implements ArrayAccess
{
    public function offsetGet($offset)
    {
    }
}
CODE_SAMPLE
,
                <<<'CODE_SAMPLE'
class SomeClass implements ArrayAccess
{
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
    }
}
CODE_SAMPLE
            ,
                [
                    'ArrayAccess' => ['offsetGet'],
                ]
            ),
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [ClassMethod::class];
    }

    /**
     * @param ClassMethod $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($this->phpAttributeAnalyzer->hasPhpAttribute($node, 'ReturnTypeWillChange')) {
            return null;
        }

        $classLike = $this->betterNodeFinder->findParentByTypes($node, [Class_::class, Interface_::class]);
        if (! $classLike instanceof ClassLike) {
            return null;
        }

        $className = (string) $this->nodeNameResolver->getName($classLike);
        $objectType = new ObjectType($className);
        $methodName = $this->nodeNameResolver->getName($node);

        foreach ($this->classMethodsOfClass as $class => $methods) {
            $configuredClassObjectType = new ObjectType($class);
            if (! $configuredClassObjectType->isSuperTypeOf($objectType)) {
                continue;
            }

            if (! in_array($methodName, $methods, true)) {
                continue;
            }

            $attributeGroup = $this->phpAttributeGroupFactory->createFromClass(
                self::RETURN_TYPE_WILL_CHANGE_ATTRIBUTE
            );
            $node->attrGroups[] = $attributeGroup;

            break;
        }

        return $node;
    }

    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration): void
    {
        $this->classMethodsOfClass = $configuration;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::RETURN_TYPE_WILL_CHANGE_ATTRIBUTE;
    }
}
