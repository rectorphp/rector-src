<?php

declare(strict_types=1);

namespace Rector\Utils\PHPStan\Rule;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\CallLike;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Continue_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\Return_;
use PhpParser\NodeFinder;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Inside a Rector rule, a cheap early-return guard (isName(), instanceof, isset(), arg count)
 * that does not depend on an expensive analysis call (getType(), isObjectType(),
 * createFromNodeOrEmpty(), ...) should run BEFORE that call, so non-matching nodes bail out
 * before paying for the costly analysis.
 *
 * @implements Rule<ClassMethod>
 * @see \Rector\Utils\PHPStan\Tests\Rule\CheaperGuardFirstRule\CheaperGuardFirstRuleTest
 */
final class CheaperGuardFirstRule implements Rule
{
    /**
     * @var string
     */
    private const ERROR_MESSAGE = 'Cheap guard on line %d can run before the expensive call on line %d; move the early return up to bail before the costly analysis.';

    /**
     * Calls that trigger heavy analysis (type resolution, docblock parsing, file re-parsing).
     *
     * @var string[]
     */
    private const EXPENSIVE_CALLS = [
        'getType',
        'getNativeType',
        'isObjectType',
        'isObjectTypes',
        'createFromNode',
        'createFromNodeOrEmpty',
    ];

    /**
     * Calls cheap enough to evaluate as a pre-filter.
     *
     * @var string[]
     */
    private const CHEAP_CALLS = ['isName', 'isNames', 'isFirstClassCallable', 'in_array', 'count'];

    /**
     * @var string
     */
    private const ABSTRACT_RECTOR_CLASS = 'Rector\Rector\AbstractRector';

    public function getNodeType(): string
    {
        return ClassMethod::class;
    }

    /**
     * @param ClassMethod $node
     * @return list<IdentifierRuleError>
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if ($node->stmts === null) {
            return [];
        }

        $classReflection = $scope->getClassReflection();
        if (! $classReflection instanceof ClassReflection) {
            return [];
        }

        if (! in_array(self::ABSTRACT_RECTOR_CLASS, $classReflection->getParentClassesNames(), true)) {
            return [];
        }

        $stmts = $node->stmts;
        $anchorIndex = $this->findExpensiveAnchorIndex($stmts);
        if ($anchorIndex === null) {
            return [];
        }

        $assignedVariableNames = $this->resolveAssignedVariableNames($stmts[$anchorIndex]);

        for ($index = $anchorIndex + 1; $index < count($stmts); ++$index) {
            $stmt = $stmts[$index];

            if ($this->isPureBailGuard($stmt)) {
                /** @var If_ $stmt */
                if ($this->isCheapCondition($stmt->cond) && $this->isIndependent($stmt->cond, $assignedVariableNames)) {
                    return [
                        RuleErrorBuilder::message(
                            sprintf(self::ERROR_MESSAGE, $stmt->getStartLine(), $stmts[$anchorIndex]->getStartLine())
                        )
                            ->identifier('rector.cheaperGuardFirst')
                            ->line($stmt->getStartLine())
                            ->build(),
                    ];
                }

                // a dependent or non-cheap bail guard is legitimately here; keep scanning
                continue;
            }

            if ($stmt instanceof Expression && $stmt->expr instanceof Assign) {
                $assignedVariableNames = [...$assignedVariableNames, ...$this->resolveAssignedVariableNames($stmt)];
                continue;
            }

            // any other statement (value return, transformation, loop) makes hoisting unsafe
            return [];
        }

        return [];
    }

    /**
     * @param Stmt[] $stmts
     */
    private function findExpensiveAnchorIndex(array $stmts): ?int
    {
        foreach ($stmts as $index => $stmt) {
            if (! $stmt instanceof Expression && ! $stmt instanceof If_) {
                continue;
            }

            if ($this->containsCall($stmt, self::EXPENSIVE_CALLS)) {
                return $index;
            }
        }

        return null;
    }

    private function isPureBailGuard(Stmt $stmt): bool
    {
        if (! $stmt instanceof If_) {
            return false;
        }

        if ($stmt->elseifs !== [] || $stmt->else !== null) {
            return false;
        }

        if (count($stmt->stmts) !== 1) {
            return false;
        }

        $onlyStmt = $stmt->stmts[0];
        if ($onlyStmt instanceof Continue_) {
            return true;
        }

        if (! $onlyStmt instanceof Return_) {
            return false;
        }

        // bare "return;" or "return null;"
        if (! $onlyStmt->expr instanceof Node) {
            return true;
        }

        return $onlyStmt->expr instanceof ConstFetch && $onlyStmt->expr->name->toLowerString() === 'null';
    }

    private function isCheapCondition(Expr $cond): bool
    {
        $nodeFinder = new NodeFinder();
        $callLikes = $nodeFinder->findInstanceOf($cond, CallLike::class);

        foreach ($callLikes as $callLike) {
            $name = $this->resolveCallName($callLike);
            if ($name === null) {
                return false;
            }

            if (! in_array($name, self::CHEAP_CALLS, true)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string[] $assignedVariableNames
     */
    private function isIndependent(Expr $cond, array $assignedVariableNames): bool
    {
        if ($assignedVariableNames === []) {
            return true;
        }

        $nodeFinder = new NodeFinder();
        $variables = $nodeFinder->findInstanceOf($cond, Variable::class);

        foreach ($variables as $variable) {
            if (! is_string($variable->name)) {
                continue;
            }

            if (in_array($variable->name, $assignedVariableNames, true)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string[] $callNames
     */
    private function containsCall(Node $node, array $callNames): bool
    {
        $nodeFinder = new NodeFinder();
        $callLikes = $nodeFinder->findInstanceOf($node, CallLike::class);

        foreach ($callLikes as $callLike) {
            $name = $this->resolveCallName($callLike);
            if ($name !== null && in_array($name, $callNames, true)) {
                return true;
            }
        }

        return false;
    }

    private function resolveCallName(CallLike $callLike): ?string
    {
        if ($callLike instanceof Node\Expr\MethodCall || $callLike instanceof Node\Expr\NullsafeMethodCall || $callLike instanceof Node\Expr\StaticCall) {
            return $callLike->name instanceof Identifier ? $callLike->name->toString() : null;
        }

        if ($callLike instanceof Node\Expr\FuncCall) {
            return $callLike->name instanceof Node\Name ? $callLike->name->toString() : null;
        }

        return null;
    }

    /**
     * @return string[]
     */
    private function resolveAssignedVariableNames(Stmt $stmt): array
    {
        if (! $stmt instanceof Expression || ! $stmt->expr instanceof Assign) {
            return [];
        }

        $assign = $stmt->expr;
        if ($assign->var instanceof Variable && is_string($assign->var->name)) {
            return [$assign->var->name];
        }

        return [];
    }
}
