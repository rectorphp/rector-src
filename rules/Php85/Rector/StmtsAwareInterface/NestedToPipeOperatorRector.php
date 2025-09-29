<?php

declare(strict_types=1);

namespace Rector\Php85\Rector\StmtsAwareInterface;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\VariadicPlaceholder;
use Rector\Contract\PhpParser\Node\StmtsAwareInterface;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\PhpVersionFeature;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see https://wiki.php.net/rfc/pipe-operator-v3
 * @see \Rector\Tests\Php85\Rector\StmtsAwareInterface\NestedToPipeOperatorRector\NestedToPipeOperatorRectorTest
 */
final class NestedToPipeOperatorRector extends AbstractRector implements MinPhpVersionInterface
{
    public function getRuleDefinition(): RuleDefinition
    { 
        return new RuleDefinition(
            'Transform nested function calls and sequential assignments to pipe operator syntax',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
$value = "hello world";
$result1 = function3($value);
$result2 = function2($result1);
$result = function1($result2);
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$value = "hello world";

$result = $value
    |> function3(...)
    |> function2(...)
    |> function1(...);
CODE_SAMPLE
                ),
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [StmtsAwareInterface::class];
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::PIPE_OPERATOER;
    }

    public function refactor(Node $node): ?Node
    {
        if (! $node instanceof StmtsAwareInterface || $node->stmts === null) {
            return null;
        }

        $hasChanged = false;

        // First, try to transform sequential assignments
        $sequentialChanged = $this->transformSequentialAssignments($node);
        if ($sequentialChanged) {
            $hasChanged = true;
        }

        // Then, transform nested function calls
        $nestedChanged = $this->transformNestedCalls($node);
        if ($nestedChanged) {
            $hasChanged = true;
        }

        return $hasChanged ? $node : null;
    }

    private function transformSequentialAssignments(StmtsAwareInterface $node): bool
    {
        $hasChanged = false;
        $statements = $node->stmts;
        $totalStatements = count($statements) - 1;

        for ($i = 0; $i < $totalStatements; ++$i) {
            $chain = $this->findAssignmentChain($statements, $i);

            if ($chain && count($chain) >= 2) {
                $this->processAssignmentChain($node, $chain, $i);
                $hasChanged = true;
                // Skip processed statements
                $i += count($chain) - 1;
            }
        }

        return $hasChanged;
    }

    /**
     * @param array<int, Stmt> $statements
     * @return array<int, Stmt>|null
     */
    private function findAssignmentChain(array $statements, int $startIndex): ?array
    {
        $chain = [];
        $currentIndex = $startIndex;
        $totalStatements = count($statements);

        while ($currentIndex < $totalStatements) {
            $stmt = $statements[$currentIndex];

            if (! $stmt instanceof Expression) {
                break;
            }

            $expr = $stmt->expr;
            if (! $expr instanceof Assign) {
                break;
            }

            // Check if this is a simple function call with one argument
            if (! $expr->expr instanceof FuncCall) {
                break;
            }

            $funcCall = $expr->expr;
            if (count($funcCall->args) !== 1) {
                break;
            }

            $arg = $funcCall->args[0];

            if ($currentIndex === $startIndex) {
                // First in chain - must be a variable or simple value
                if (! $arg->value instanceof Variable && ! $this->isSimpleValue($arg->value)) {
                    return null;
                }
                $chain[] = [
                    'stmt' => $stmt,
                    'assign' => $expr,
                    'funcCall' => $funcCall,
                ];
            } else {
                // Subsequent in chain - must use previous assignment's variable
                $previousAssign = $chain[count($chain) - 1]['assign'];
                $previousVarName = $this->getName($previousAssign->var);

                if (! $arg->value instanceof Variable || $this->getName(
                    $arg->value
                ) !== $previousVarName) {
                    break;
                }
                $chain[] = [
                    'stmt' => $stmt,
                    'assign' => $expr,
                    'funcCall' => $funcCall,
                ];
            }

            $currentIndex += 1;
        }

        return $chain;
    }

    private function isSimpleValue(Node $node): bool
    {
        return $node instanceof Node\Scalar\String_
            || $node instanceof Node\Scalar\LNumber
            || $node instanceof Node\Scalar\DNumber
            || $node instanceof Node\Expr\ConstFetch
            || $node instanceof Node\Expr\Array_;
    }

    private function processAssignmentChain(StmtsAwareInterface $node, array $chain, int $startIndex): void
    {
        $firstAssignment = $chain[0]['assign'];
        $lastAssignment = $chain[count($chain) - 1]['assign'];

        // Get the initial value from the first function call's argument
        $firstFuncCall = $chain[0]['funcCall'];
        $initialValue = $firstFuncCall->args[0]->value;

        // Build the pipe chain
        $pipeExpression = $initialValue;

        foreach ($chain as $chainItem) {
            $funcCall = $chainItem['funcCall'];
            $placeholderCall = $this->createPlaceholderCall($funcCall);
            $pipeExpression = new Node\Expr\BinaryOp\Pipe($pipeExpression, $placeholderCall);
        }

        // Create the final assignment
        $finalAssignment = new Assign($lastAssignment->var, $pipeExpression);
        $finalExpression = new Expression($finalAssignment);

        // Replace the statements
        $endIndex = $startIndex + count($chain) - 1;

        // Remove all intermediate statements and replace with the final pipe expression
        for ($i = $startIndex; $i <= $endIndex; $i++) {
            if ($i === $startIndex) {
                $node->stmts[$i] = $finalExpression;
            } else {
                unset($node->stmts[$i]);
            }
        }

        $stmts = array_values($node->stmts);

        // Reindex the array
        $node->stmts = $stmts;
    }

    private function transformNestedCalls(StmtsAwareInterface $node): bool
    {
        $hasChanged = false;

        foreach ($node->stmts as $stmt) {
            if (! $stmt instanceof Expression) {
                continue;
            }

            $expr = $stmt->expr;

            if ($expr instanceof Assign) {
                $assignedValue = $expr->expr;
                $processedValue = $this->processNestedCalls($assignedValue);

                if ($processedValue !== null && $processedValue !== $assignedValue) {
                    $expr->expr = $processedValue;
                    $hasChanged = true;
                }
            } elseif ($expr instanceof FuncCall) {
                $processedValue = $this->processNestedCalls($expr);
                if ($processedValue !== null && $processedValue !== $expr) {
                    $stmt->expr = $processedValue;
                    $hasChanged = true;
                }
            }
        }

        return $hasChanged;
    }

    private function processNestedCalls(Node $node): ?Node
    {
        if (! $node instanceof FuncCall) {
            return null;
        }

        // Check if any argument is a function call
        foreach ($node->args as $arg) {
            if ($arg->value instanceof FuncCall) {
                return $this->buildPipeExpression($node, $arg->value);
            }
        }

        return null;
    }

    private function buildPipeExpression(FuncCall $outerCall, FuncCall $innerCall): Node\Expr\BinaryOp\Pipe
    {
        $pipe = new Node\Expr\BinaryOp\Pipe($innerCall, $this->createPlaceholderCall($outerCall));

        return $pipe;
    }

    private function createPlaceholderCall(FuncCall $originalCall): FuncCall
    {
        $newArgs = [];
        foreach ($originalCall->args as $arg) {
            $newArgs[] = new VariadicPlaceholder();
        }

        return new FuncCall($originalCall->name, $newArgs);
    }
}
