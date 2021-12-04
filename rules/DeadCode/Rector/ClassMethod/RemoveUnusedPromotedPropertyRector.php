<?php

declare(strict_types=1);

namespace Rector\DeadCode\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\Core\NodeManipulator\PropertyManipulator;
use Rector\Core\PhpParser\NodeFinder\PropertyFetchFinder;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\MethodName;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPromotedPropertyRector\RemoveUnusedPromotedPropertyRectorTest
 */
final class RemoveUnusedPromotedPropertyRector extends AbstractRector implements MinPhpVersionInterface
{
    public function __construct(
        private readonly PropertyFetchFinder $propertyFetchFinder,
        private readonly PropertyManipulator $propertyManipulator,
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
        $hasRemovedProperty = false;

        if (! $this->isName($node, MethodName::CONSTRUCT)) {
            return null;
        }

        $class = $this->betterNodeFinder->findParentType($node, Class_::class);
        if (! $class instanceof Class_) {
            return null;
        }

        foreach ($node->getParams() as $param) {
            // only private local scope; removing public property might be dangerous
            if ($param->flags !== Class_::MODIFIER_PRIVATE) {
                continue;
            }

            if ($this->propertyManipulator->isPropertyUsedInReadContext($param)) {
                continue;
            }

            $paramName = $this->getName($param);

            $propertyFetches = $this->propertyFetchFinder->findLocalPropertyFetchesByName($class, $paramName);
            if ($propertyFetches !== []) {
                continue;
            }

            // is variable used? only remove property, keep param
            $variable = $this->betterNodeFinder->findVariableOfName((array) $node->stmts, $paramName);
            if ($variable instanceof Variable) {
                $param->flags = 0;
                continue;
            }

            // remove param
            $this->removeNode($param);
            $hasRemovedProperty = true;
        }

        if ($hasRemovedProperty) {
            return $node;
        }

        return null;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::PROPERTY_PROMOTION;
    }
}
