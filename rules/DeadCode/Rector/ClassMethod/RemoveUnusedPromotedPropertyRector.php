<?php

declare(strict_types=1);

namespace Rector\DeadCode\Rector\ClassMethod;

use PhpParser\Node;
use Rector\Core\PhpParser\NodeFinder\PropertyFetchFinder;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\MethodName;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPromotedPropertyRector\RemoveUnusedPromotedPropertyRectorTest
 */
final class RemoveUnusedPromotedPropertyRector extends AbstractRector
{
    public function __construct(
        private PropertyFetchFinder $propertyFetchFinder
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove unused promoted property', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function __construct(
        private $someUnusedDependency,
        private $usedDependency
    ) {
    }

    public function getUsedDependency()
    {
        return $this->usedDependency;
    }
}
CODE_SAMPLE

                ,
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function __construct(
        private $usedDependency
    ) {
    }

    public function getUsedDependency()
    {
        return $this->usedDependency;
    }
}
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return array<class-string<\PhpParser\Node>>
     */
    public function getNodeTypes(): array
    {
        return [\PhpParser\Node\Stmt\ClassMethod::class];
    }

    /**
     * @param \PhpParser\Node\Stmt\ClassMethod $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isAtLeastPhpVersion(PhpVersionFeature::PROPERTY_PROMOTION)) {
            return null;
        }

        if (! $this->isName($node, MethodName::CONSTRUCT)) {
            return null;
        }

        $class = $node->getAttribute(AttributeKey::CLASS_NODE);
        if (! $class instanceof Node\Stmt\Class_) {
            return null;
        }

        foreach ($node->getParams() as $param) {
            if (! $param->flags) {
                continue;
            }

            // only private local scope; removing public property might be dangerous
            if ($param->flags !== Node\Stmt\Class_::MODIFIER_PRIVATE) {
                continue;
            }

            $paramName = $this->getName($param);

            $propertyFetches = $this->propertyFetchFinder->findLocalPropertyFetchesByName($class, $paramName);
            if ($propertyFetches !== []) {
                continue;
            }

            // remove param
            $this->removeNode($param);
        }

        return $node;
    }
}
