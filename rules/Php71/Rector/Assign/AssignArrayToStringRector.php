<?php

declare(strict_types=1);

namespace Rector\Php71\Rector\Assign;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Property;
use Rector\Core\PhpParser\NodeFinder\PropertyFetchFinder;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @changelog https://stackoverflow.com/a/41000866/1348344 https://3v4l.org/ABDNv
 *
 * @see \Rector\Tests\Php71\Rector\Assign\AssignArrayToStringRector\AssignArrayToStringRectorTest
 */
final class AssignArrayToStringRector extends AbstractRector implements MinPhpVersionInterface
{
    public function __construct(
        private readonly PropertyFetchFinder $propertyFetchFinder,
    ) {
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::NO_ASSIGN_ARRAY_TO_STRING;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'String cannot be turned into array by assignment anymore',
            [new CodeSample(
                <<<'CODE_SAMPLE'
$string = '';
$string[] = 1;
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
$string = [];
$string[] = 1;
CODE_SAMPLE
            )]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [Assign::class, Class_::class];
    }

    /**
     * @param Assign|Class_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node instanceof Class_) {
            return $this->refactorClass($node);
        }

        if (! $this->isEmptyString($node->expr)) {
            return null;
        }

        $assignedVar = $node->var;
        if (! $assignedVar instanceof Variable) {
            return null;
        }

        // 1. variable!
        $shouldRetype = false;

        /** @var array<Variable|PropertyFetch|StaticPropertyFetch> $exprUsages */
        $exprUsages = $this->betterNodeFinder->findSameNamedVariables($assignedVar);

        // detect if is part of variable assign?
        foreach ($exprUsages as $exprUsage) {
            $parentNode = $exprUsage->getAttribute(AttributeKey::PARENT_NODE);
            if (! $parentNode instanceof ArrayDimFetch) {
                continue;
            }

            $firstAssign = $this->betterNodeFinder->findParentType($parentNode, Assign::class);
            if (! $firstAssign instanceof Assign) {
                continue;
            }

            // skip explicit assigns
            if ($parentNode->dim instanceof Expr) {
                continue;
            }

            $shouldRetype = true;
            break;
        }

        if (! $shouldRetype) {
            return null;
        }

        $node->expr = new Array_();
        return $node;
    }

    private function isEmptyString(Expr $expr): bool
    {
        if (! $expr instanceof String_) {
            return false;
        }

        return $expr->value === '';
    }

    private function refactorClass(Class_ $class): ?Class_
    {
        $hasChanged = false;

        foreach ($class->getProperties() as $property) {
            if (! $this->hasPropertyDefaultEmptyString($property)) {
                continue;
            }

            $arrayDimFetches = $this->propertyFetchFinder->findLocalPropertyArrayDimFetchesAssignsByName(
                $class,
                $property
            );

            foreach ($arrayDimFetches as $arrayDimFetch) {
                if ($arrayDimFetch->dim instanceof Expr) {
                    continue;
                }

                $property->props[0]->default = new Array_();
                $hasChanged = true;
            }
        }

        if ($hasChanged) {
            return $class;
        }

        return null;
    }

    private function hasPropertyDefaultEmptyString(Property $property): bool
    {
        $defaultExpr = $property->props[0]->default;
        if (! $defaultExpr instanceof Expr) {
            return false;
        }

        return $this->isEmptyString($defaultExpr);
    }
}
