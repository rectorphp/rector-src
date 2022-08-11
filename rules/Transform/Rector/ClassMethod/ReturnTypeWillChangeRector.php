<?php

declare(strict_types=1);

namespace Rector\Transform\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Interface_;
use PHPStan\Type\ObjectType;
use Rector\Core\Contract\Rector\AllowEmptyConfigurableRectorInterface;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\Php80\NodeAnalyzer\PhpAttributeAnalyzer;
use Rector\Php81\Enum\AttributeName;
use Rector\PhpAttribute\NodeFactory\PhpAttributeGroupFactory;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\Tests\Transform\Rector\ClassMethod\ReturnTypeWillChangeRector\ReturnTypeWillChangeRectorTest
 */
final class ReturnTypeWillChangeRector extends AbstractRector implements AllowEmptyConfigurableRectorInterface, MinPhpVersionInterface
{
    /**
     * @var string[]
     */
    private array $returnTypeChangedClasses;

    public function __construct(
        private readonly PhpAttributeAnalyzer $phpAttributeAnalyzer,
        private readonly PhpAttributeGroupFactory $phpAttributeGroupFactory
    ) {
        $this->returnTypeChangedClasses[] = 'ArrayAccess';
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Add #[\ReturnTypeWillChange] attribute to configured instanceof class with methods',
            [
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
                ['ArrayAccess']
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
        if ($this->phpAttributeAnalyzer->hasPhpAttribute($node, AttributeName::RETURN_TYPE_WILL_CHANGE)) {
            return null;
        }

        // the return type is known, no need to add attribute
        if ($node->returnType !== null) {
            return null;
        }

        $classLike = $this->betterNodeFinder->findParentByTypes($node, [Class_::class, Interface_::class]);
        if (! $classLike instanceof ClassLike) {
            return null;
        }

        $className = (string) $this->nodeNameResolver->getName($classLike);
        $objectType = new ObjectType($className);
        $methodName = $node->name->toString();

        $hasChanged = false;

        foreach ($this->returnTypeChangedClasses as $returnTypeChangedClass) {
            $configuredClassObjectType = new ObjectType($returnTypeChangedClass);
            if (! $configuredClassObjectType->isSuperTypeOf($objectType)->yes()) {
                continue;
            }

            $node->attrGroups[] = $this->phpAttributeGroupFactory->createFromClass(
                AttributeName::RETURN_TYPE_WILL_CHANGE
            );

            $hasChanged = true;

            break;
        }

        if (! $hasChanged) {
            return null;
        }

        return $node;
    }

    /**
     * @param string[] $configuration
     */
    public function configure(array $configuration): void
    {
        Assert::allString($configuration);
        $this->returnTypeChangedClasses = array_merge($this->returnTypeChangedClasses, $configuration);
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::RETURN_TYPE_WILL_CHANGE_ATTRIBUTE;
    }
}
