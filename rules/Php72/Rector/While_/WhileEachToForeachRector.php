<?php

declare(strict_types=1);

namespace Rector\Php72\Rector\While_;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\ArrayItem;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Foreach_;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\While_;
use PhpParser\NodeVisitor;
use Rector\NodeManipulator\AssignManipulator;
use Rector\Php72\ValueObject\ListAndEach;
use Rector\PhpParser\Enum\NodeGroup;
use Rector\PhpParser\Node\FileNode;
use Rector\Rector\AbstractRector;
use Rector\RuleDoc\CodeSample\CodeSample;
use Rector\RuleDoc\RuleDefinition;
use Rector\ValueObject\PhpVersion;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Webmozart\Assert\Assert;

/**
 * @see \Rector\Tests\Php72\Rector\While_\WhileEachToForeachRector\WhileEachToForeachRectorTest
 */
final class WhileEachToForeachRector extends AbstractRector implements MinPhpVersionInterface
{
    /**
     * @var string[]
     */
    private const array POINTER_FUNCTION_NAMES = ['current', 'pos', 'key', 'next', 'prev', 'end', 'reset', 'each'];

    public function __construct(
        private readonly AssignManipulator $assignManipulator
    ) {
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersion::PHP_72;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Use `foreach()` instead of deprecated `each()`, when the array pointer is reset right before the loop',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
reset($callbacks);
while (list($key, $callback) = each($callbacks)) {
    // ...
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
reset($callbacks);
foreach ($callbacks as $key => $callback) {
    // ...
}
CODE_SAMPLE
                ),
                new CodeSample(
                    <<<'CODE_SAMPLE'
reset($callbacks);
while (list($key) = each($callbacks)) {
    // ...
}
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
reset($callbacks);
foreach (array_keys($callbacks) as $key) {
    // ...
}
CODE_SAMPLE
                ),
            ]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [FileNode::class, Namespace_::class, ClassMethod::class, Function_::class, Closure::class];
    }

    /**
     * @param FileNode|Namespace_|ClassMethod|Function_|Closure $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node->stmts === null) {
            return null;
        }

        if ($node instanceof FileNode && $node->isNamespaced()) {
            // handled in Namespace_
            return null;
        }

        $stmtsAwareNodes = [$node];
        $this->traverseNodesWithCallable($node->stmts, function (Node $subNode) use (&$stmtsAwareNodes): ?int {
            if ($subNode instanceof Class_ || $subNode instanceof Function_ || $subNode instanceof Closure) {
                return NodeVisitor::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
            }

            if (NodeGroup::isStmtAwareNode($subNode)) {
                $stmtsAwareNodes[] = $subNode;
            }

            return null;
        });

        $hasChanged = false;
        // inner blocks first, so a replaced outer loop carries their changes
        foreach (array_reverse($stmtsAwareNodes) as $stmtsAwareNode) {
            Assert::propertyExists($stmtsAwareNode, 'stmts');
            if ($stmtsAwareNode->stmts === null) {
                continue;
            }

            $previousStmt = null;
            foreach ($stmtsAwareNode->stmts as $key => $stmt) {
                if ($stmt instanceof While_ && $previousStmt instanceof Stmt) {
                    $foreach = $this->refactorWhile($stmt, $previousStmt, $node);
                    if ($foreach instanceof Foreach_) {
                        $stmtsAwareNode->stmts[$key] = $foreach;
                        $hasChanged = true;
                    }
                }

                $previousStmt = $stmt;
            }
        }

        if ($hasChanged) {
            return $node;
        }

        return null;
    }

    private function refactorWhile(
        While_ $while,
        Stmt $previousStmt,
        FileNode|Namespace_|ClassMethod|Function_|Closure $scopeNode
    ): ?Foreach_ {
        if (! $while->cond instanceof Assign) {
            return null;
        }

        $listAndEach = $this->assignManipulator->matchListAndEach($while->cond);
        if (! $listAndEach instanceof ListAndEach) {
            return null;
        }

        $eachFuncCall = $listAndEach->getEachFuncCall();

        $list = $listAndEach->getList();
        if (! isset($eachFuncCall->getArgs()[0])) {
            return null;
        }

        $firstArg = $eachFuncCall->getArgs()[0];

        // each() continues from the internal pointer, foreach always starts at the first item
        if (! $this->isPointerAtStart($firstArg->value, $previousStmt, $scopeNode, $eachFuncCall)) {
            return null;
        }

        $foreachedExpr = count($list->items) === 1 ? $this->nodeFactory->createFuncCall(
            'array_keys',
            [$firstArg]
        ) : $firstArg->value;

        $arrayItem = array_pop($list->items);

        $isTrailingCommaLast = false;

        if (! $arrayItem instanceof ArrayItem) {
            $foreachedExpr = $this->nodeFactory->createFuncCall('array_keys', [$eachFuncCall->args[0]]);
            /** @var ArrayItem $arrayItem */
            $arrayItem = current($list->items);
            $isTrailingCommaLast = true;
        }

        $foreach = new Foreach_($foreachedExpr, $arrayItem->value, [
            'stmts' => $while->stmts,
        ]);

        $this->mirrorComments($foreach, $while);

        // is key included? add it to foreach
        if ($list->items !== []) {
            /** @var ArrayItem|null $keyItem */
            $keyItem = array_pop($list->items);

            if ($keyItem instanceof ArrayItem && ! $isTrailingCommaLast) {
                $foreach->keyVar = $keyItem->value;
            }
        }

        return $foreach;
    }

    private function isPointerAtStart(
        Expr $expr,
        Stmt $previousStmt,
        FileNode|Namespace_|ClassMethod|Function_|Closure $scopeNode,
        FuncCall $eachFuncCall
    ): bool {
        if (! $expr instanceof Variable || ! is_string($expr->name)) {
            return false;
        }

        $variableName = $expr->name;
        if (! $previousStmt instanceof Expression) {
            return false;
        }

        $resetFuncCall = $this->matchResetFuncCall($previousStmt->expr, $variableName);
        if (! $resetFuncCall instanceof FuncCall && ! $this->isArrayLiteralAssign($previousStmt->expr, $variableName)) {
            return false;
        }

        // the caller sees the pointer of a by-reference variable
        if (! $scopeNode instanceof FileNode && ! $scopeNode instanceof Namespace_) {
            foreach ($scopeNode->params as $param) {
                if ($param->byRef && $this->isName($param->var, $variableName)) {
                    return false;
                }
            }
        }

        if ($scopeNode instanceof Closure) {
            foreach ($scopeNode->uses as $closureUse) {
                if ($closureUse->byRef && $this->isName($closureUse->var, $variableName)) {
                    return false;
                }
            }
        }

        // any other pointer call on the variable would see a different position
        $hasOtherPointerCall = false;
        $this->traverseNodesWithCallable((array) $scopeNode->stmts, function (Node $node) use (
            $variableName,
            $resetFuncCall,
            $eachFuncCall,
            &$hasOtherPointerCall
        ): ?int {
            if ($node instanceof Class_ || $node instanceof Function_) {
                return NodeVisitor::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
            }

            if (! $node instanceof FuncCall || $node === $resetFuncCall || $node === $eachFuncCall) {
                return null;
            }

            if (! $this->isPointerFuncCallOnVariable($node, self::POINTER_FUNCTION_NAMES, $variableName)) {
                return null;
            }

            $hasOtherPointerCall = true;
            return NodeVisitor::STOP_TRAVERSAL;
        });

        return ! $hasOtherPointerCall;
    }

    private function matchResetFuncCall(Expr $expr, string $variableName): ?FuncCall
    {
        if (! $expr instanceof FuncCall) {
            return null;
        }

        if (! $this->isPointerFuncCallOnVariable($expr, ['reset'], $variableName)) {
            return null;
        }

        return $expr;
    }

    private function isArrayLiteralAssign(Expr $expr, string $variableName): bool
    {
        if (! $expr instanceof Assign || ! $expr->expr instanceof Array_) {
            return false;
        }

        return $expr->var instanceof Variable && $this->isName($expr->var, $variableName);
    }

    /**
     * @param string[] $functionNames
     */
    private function isPointerFuncCallOnVariable(FuncCall $funcCall, array $functionNames, string $variableName): bool
    {
        if (! $this->isNames($funcCall, $functionNames) || $funcCall->isFirstClassCallable()) {
            return false;
        }

        $firstArg = $funcCall->getArgs()[0] ?? null;
        if (! $firstArg instanceof Arg || ! $firstArg->value instanceof Variable) {
            return false;
        }

        return $this->isName($firstArg->value, $variableName);
    }
}
