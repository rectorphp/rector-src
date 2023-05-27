<?php

declare(strict_types=1);

namespace Rector\Php71\Rector\Assign;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Property;
use Rector\Core\PhpParser\NodeFinder\PropertyFetchFinder;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\ValueObject\PhpVersionFeature;
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

        return $this->refactorAssign($node);
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

    /**
     * @return ArrayDimFetch[]
     */
    private function findSameNamedVariableAssigns(Variable $variable): array
    {
        // assign of empty string to something
        $scopeStmt = $this->findParentScope($variable);
        if (! $scopeStmt instanceof Stmt) {
            return [];
        }

        $variableName = $this->nodeNameResolver->getName($variable);
        if ($variableName === null) {
            return [];
        }

        $assignedArrayDimFetches = [];

        $this->traverseNodesWithCallable($scopeStmt, function (Node $node) use (
            $variableName,
            &$assignedArrayDimFetches
        ) {
            if (! $node instanceof Assign) {
                return null;
            }

            if (! $node->var instanceof ArrayDimFetch) {
                return null;
            }

            $arrayDimFetch = $node->var;
            if (! $arrayDimFetch->var instanceof Variable) {
                return null;
            }

            if (! $this->isName($arrayDimFetch->var, $variableName)) {
                return null;
            }

            $assignedArrayDimFetches[] = $arrayDimFetch;
        });

        return $assignedArrayDimFetches;
    }

    /**
     * @return Function_|ClassMethod|Class_|Namespace_|null
     */
    private function findParentScope(Variable $variable): Stmt|null
    {
        return $this->betterNodeFinder->findParentByTypes($variable, [
            Function_::class,
            ClassMethod::class,
            Class_::class,
            Namespace_::class,
        ]);
    }

    private function refactorAssign(Assign $assign): ?Assign
    {
        if (! $this->isEmptyString($assign->expr)) {
            return null;
        }

        if (! $assign->var instanceof Variable) {
            return null;
        }

        $variableAssignArrayDimFetches = $this->findSameNamedVariableAssigns($assign->var);

        $shouldRetype = false;

        // detect if is part of variable assign?
        foreach ($variableAssignArrayDimFetches as $variableAssignArrayDimFetch) {
            if ($variableAssignArrayDimFetch->dim instanceof Expr) {
                continue;
            }

            $shouldRetype = true;
            break;
        }

        if (! $shouldRetype) {
            return null;
        }

        $assign->expr = new Array_();
        return $assign;
    }
}
