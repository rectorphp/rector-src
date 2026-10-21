<?php

declare(strict_types=1);

namespace Rector\Utils\PHPStan\Rule;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\Foreach_;
use PhpParser\NodeFinder;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use Rector\Contract\Rector\RectorInterface;

/**
 * Inside a Rector rule, the refactored class reflection is identical for every
 * loop iteration, so resolving it inside a foreach() is wasted work. Hoist the
 * call above the loop.
 *
 * @implements Rule<Foreach_>
 * @see \Rector\Utils\PHPStan\Tests\Rule\NoResolveClassReflectionInForeachRule\NoResolveClassReflectionInForeachRuleTest
 */
final readonly class NoResolveClassReflectionInForeachRule implements Rule
{
    private const string ERROR_MESSAGE = 'resolveClassReflection() in a foreach() returns the same value every iteration, so the null guard that continues repeats pointlessly. Hoist the call above the loop and return null once.';

    private const string METHOD_NAME = 'resolveClassReflection';

    private NodeFinder $nodeFinder;

    public function __construct()
    {
        $this->nodeFinder = new NodeFinder();
    }

    public function getNodeType(): string
    {
        return Foreach_::class;
    }

    /**
     * @param Foreach_ $node
     * @return list<IdentifierRuleError>
     */
    public function processNode(Node $node, Scope $scope): array
    {
        $classReflection = $scope->getClassReflection();
        if (! $classReflection instanceof ClassReflection) {
            return [];
        }

        // only Rector rules resolve the single refactored class per node
        if (! $classReflection->implementsInterface(RectorInterface::class)) {
            return [];
        }

        $ruleErrors = [];
        foreach ($this->matchResolveClassReflectionCalls($node) as $methodCall) {
            $ruleErrors[] = RuleErrorBuilder::message(self::ERROR_MESSAGE)
                ->identifier('rector.resolveClassReflectionInForeach')
                ->line($methodCall->getStartLine())
                ->build();
        }

        return $ruleErrors;
    }

    /**
     * @return MethodCall[]
     */
    private function matchResolveClassReflectionCalls(Foreach_ $foreach): array
    {
        // skip calls nested in an inner foreach, that inner one reports them
        $nestedCallObjectIds = [];
        foreach ($this->nodeFinder->findInstanceOf($foreach->stmts, Foreach_::class) as $nestedForeach) {
            foreach ($this->nodeFinder->findInstanceOf($nestedForeach->stmts, MethodCall::class) as $nestedCall) {
                $nestedCallObjectIds[spl_object_id($nestedCall)] = true;
            }
        }

        $matchedCalls = [];
        foreach ($this->nodeFinder->findInstanceOf($foreach->stmts, MethodCall::class) as $methodCall) {
            if (! $methodCall->name instanceof Identifier) {
                continue;
            }

            if ($methodCall->name->toString() !== self::METHOD_NAME) {
                continue;
            }

            if (isset($nestedCallObjectIds[spl_object_id($methodCall)])) {
                continue;
            }

            $matchedCalls[] = $methodCall;
        }

        return $matchedCalls;
    }
}
