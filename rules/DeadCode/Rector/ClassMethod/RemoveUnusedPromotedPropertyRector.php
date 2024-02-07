<?php

declare(strict_types=1);

namespace Rector\DeadCode\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\TraitUse;
use PHPStan\Analyser\Scope;
use Rector\DeadCode\NodeAnalyzer\PropertyWriteonlyAnalyzer;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\PhpParser\NodeFinder\PropertyFetchFinder;
use Rector\Privatization\NodeManipulator\VisibilityManipulator;
use Rector\Rector\AbstractScopeAwareRector;
use Rector\ValueObject\MethodName;
use Rector\ValueObject\PhpVersionFeature;
use Rector\ValueObject\Visibility;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPromotedPropertyRector\RemoveUnusedPromotedPropertyRectorTest
 */
final class RemoveUnusedPromotedPropertyRector extends AbstractScopeAwareRector implements MinPhpVersionInterface
{
    public function __construct(
        private readonly PropertyFetchFinder $propertyFetchFinder,
        private readonly VisibilityManipulator $visibilityManipulator,
        private readonly PropertyWriteonlyAnalyzer $propertyWriteonlyAnalyzer,
        private readonly BetterNodeFinder $betterNodeFinder,
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
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactorWithScope(Node $node, Scope $scope): ?Node
    {
        $constructClassMethod = $node->getMethod(MethodName::CONSTRUCT);
        if (! $constructClassMethod instanceof ClassMethod) {
            return null;
        }

        if ($this->shouldSkipClass($node)) {
            return null;
        }

        $hasChanged = false;

        foreach ($constructClassMethod->params as $key => $param) {
            // only private local scope; removing public property might be dangerous
            if (! $this->visibilityManipulator->hasVisibility($param, Visibility::PRIVATE)) {
                continue;
            }

            $paramName = $this->getName($param);

            $propertyFetches = $this->propertyFetchFinder->findLocalPropertyFetchesByName($node, $paramName);
            if ($propertyFetches !== []) {
                continue;
            }

            if (! $this->propertyWriteonlyAnalyzer->arePropertyFetchesExclusivelyBeingAssignedTo($propertyFetches)) {
                continue;
            }

            // is variable used? only remove property, keep param
            $variable = $this->betterNodeFinder->findVariableOfName((array) $constructClassMethod->stmts, $paramName);
            if ($variable instanceof Variable) {
                $param->flags = 0;
                continue;
            }

            // remove param
            unset($constructClassMethod->params[$key]);

            $hasChanged = true;
        }

        if ($hasChanged) {
            return $node;
        }

        return null;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::PROPERTY_PROMOTION;
    }

    private function shouldSkipClass(Class_ $class): bool
    {
        if ($class->attrGroups !== []) {
            return true;
        }

        $magicGetMethod = $class->getMethod(MethodName::__GET);
        if ($magicGetMethod instanceof ClassMethod) {
            return true;
        }

        foreach ($class->stmts as $stmt) {
            if ($stmt instanceof TraitUse) {
                return true;
            }
        }

        return false;
    }
}
