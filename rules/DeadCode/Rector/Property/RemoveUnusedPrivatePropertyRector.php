<?php

declare(strict_types=1);

namespace Rector\DeadCode\Rector\Property;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;
use Rector\Core\Contract\Rector\AllowEmptyConfigurableRectorInterface;
use Rector\Core\NodeManipulator\PropertyManipulator;
use Rector\Core\PhpParser\NodeFinder\PropertyFetchFinder;
use Rector\Core\Rector\AbstractRector;
use Rector\DeadCode\SideEffect\SideEffectNodeDetector;
use Rector\Removing\NodeManipulator\ComplexNodeRemover;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\DeadCode\Rector\Property\RemoveUnusedPrivatePropertyRector\RemoveUnusedPrivatePropertyRectorTest
 */
final class RemoveUnusedPrivatePropertyRector extends AbstractRector implements AllowEmptyConfigurableRectorInterface
{
    /**
     * @var string
     */
    public const REMOVE_ASSIGN_SIDE_EFFECT = 'remove_assign_side_effect';

    /**
     * Default to true, which apply remove assign even has side effect.
     * Set to false will allow to skip when assign has side effect.
     */
    private bool $removeAssignSideEffect = true;

    public function __construct(
        private readonly PropertyManipulator $propertyManipulator,
        private readonly ComplexNodeRemover $complexNodeRemover,
        private readonly SideEffectNodeDetector $sideEffectNodeDetector,
        private readonly PropertyFetchFinder $propertyFetchFinder,
        private readonly \Rector\Core\PhpParser\NodeFinder\PropertyAssignFinder $propertyAssignFinder,
    ) {
    }

    /**
     * @param mixed[] $configuration
     */
    public function configure(array $configuration): void
    {
        $this->removeAssignSideEffect = $configuration[self::REMOVE_ASSIGN_SIDE_EFFECT] ?? (bool) current(
            $configuration
        );
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Remove unused private properties', [
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
    private $property;
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
class SomeClass
{
}
CODE_SAMPLE
,
                [
                    self::REMOVE_ASSIGN_SIDE_EFFECT => true,
                ]
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
    public function refactor(Node $node): ?Node
    {
        $hasChanged = false;

        foreach ($node->stmts as $key => $classStmt) {
            if (! $classStmt instanceof Property) {
                continue;
            }

            $property = $classStmt;
            if ($this->shouldSkipProperty($property)) {
                continue;
            }

            if ($this->propertyManipulator->isPropertyUsedInReadContext($property)) {
                continue;
            }

            $privatePropertyFetches = $this->propertyFetchFinder->findPrivatePropertyFetches($property);

            $assigns = [];

            foreach ($privatePropertyFetches as $propertyFetch) {
                $assign = $this->propertyAssignFinder->findAssign($propertyFetch);
                if (! $assign instanceof Assign) {
                    continue;
                }

                if ($assign->expr instanceof Assign) {
                    continue;
                }

                if (! $this->removeAssignSideEffect && $this->sideEffectNodeDetector->detect($assign->expr)) {
                    continue;
                }

                $assigns[] = $assign;
            }

            $this->complexNodeRemover->removePropertyAssigns($assigns);
            unset($node->stmts[$key]);
            $hasChanged = true;
        }

        if (! $hasChanged) {
            return null;
        }

        return $node;
    }

    private function shouldSkipProperty(Property $property): bool
    {
        if (count($property->props) !== 1) {
            return true;
        }

        return ! $property->isPrivate();
    }
}
