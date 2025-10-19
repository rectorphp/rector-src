<?php

declare(strict_types=1);

namespace Rector\Utils\Rector;

// e.g. to move PhpDocInfo to the particular rule itself
use PhpParser\Node;
use PhpParser\Node\ContainsStmts;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Foreach_;
use PhpParser\Node\Stmt\Function_;
use PhpParser\NodeVisitor;
use PHPStan\Type\ObjectType;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class MakeUseOfContaintsStmtsRector extends AbstractRector
{
    public function __construct(
        private readonly ValueResolver $valueResolver
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        // @see https://github.com/nikic/PHP-Parser/pull/1113
        return new RuleDefinition('Move $node->stmts to $node->getStmts() for ContaintsStmts', []);
    }

    public function getNodeTypes(): array
    {
        return [
            Identical::class,
            PropertyFetch::class,
            Function_::class,
            Assign::class,
            Foreach_::class,
            ClassMethod::class,
        ];
    }

    /**
     * @param PropertyFetch|Identical|Function_|Assign|Foreach_|ClassMethod $node
     * @return MethodCall|Identical|null|NodeVisitor::DONT_TRAVERSE_CURRENT_AND_CHILDREN
     */
    public function refactor(Node $node): MethodCall|Identical|null|int
    {
        if ($node instanceof ClassMethod) {
            if ($this->isName($node, 'getStmts')) {
                // skip getter
                return NodeVisitor::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
            }

            return null;
        }

        if ($node instanceof Foreach_) {
            return $this->refactorForeach($node);
        }

        if ($node instanceof Function_) {
            return $this->refactorFunction($node);
        }

        if ($node instanceof Assign) {
            return $this->refactorAssign($node);
        }

        if ($node instanceof Identical) {
            return $this->refactorIdentical($node);
        }

        if (! $this->isName($node->name, 'stmts')) {
            return null;
        }

        if (! $this->isObjectType($node->var, new ObjectType(ContainsStmts::class))) {
            return null;
        }

        return new MethodCall($node->var, 'getStmts');
    }

    private function isStmtsPropertyFetch(Expr $expr): bool
    {
        if (! $expr instanceof PropertyFetch) {
            return false;
        }

        return $this->isName($expr->name, 'stmts');
    }

    private function refactorIdentical(Identical $identical): ?Identical
    {
        if (! $this->valueResolver->isNull($identical->right)) {
            return null;
        }

        if ($this->isStmtsPropertyFetch($identical->left)) {
            /** @var PropertyFetch $propertyFetch */
            $propertyFetch = $identical->left;

            $identical->left = new MethodCall($propertyFetch->var, 'getStmts');
            $identical->right = new Array_([]);

            return $identical;
        }

        return null;
    }

    /**
     * @return null|NodeVisitor::DONT_TRAVERSE_CURRENT_AND_CHILDREN
     */
    private function refactorForeach(Foreach_ $foreach): ?int
    {
        if (! $this->isStmtsPropertyFetch($foreach->expr)) {
            return null;
        }
        // skip $node->stmts in foreach with key, as key is probably used on the $node->stmts
        if ($foreach->keyVar instanceof Expr) {
            return NodeVisitor::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
        }

        return null;
    }

    private function refactorFunction(Function_ $function): ?int
    {
        // keep any unset($node->stmts[x])) calls
        if ($this->isName($function->name, 'unset')) {
            return NodeVisitor::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
        }

        return null;
    }

    /**
     * @return null|NodeVisitor::DONT_TRAVERSE_CURRENT_AND_CHILDREN
     */
    private function refactorAssign(Assign $assign): ?int
    {
        if ($assign->var instanceof ArrayDimFetch) {
            $arrayDimFetch = $assign->var;
            if ($this->isStmtsPropertyFetch($arrayDimFetch->var)) {
                // keep $node->stmts[x] = ...
                return NodeVisitor::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
            }
        }

        if (! $this->isStmtsPropertyFetch($assign->var)) {
            return null;
        }

        // keep assign to $node->stmts property
        return NodeVisitor::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
    }
}
