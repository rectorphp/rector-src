<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\AssignOp\Coalesce as AssignOpCoalesce;
use PhpParser\Node\Expr\BinaryOp\Coalesce;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Identifier;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Function_;
use PhpParser\NodeTraverser;
use Rector\Core\Rector\AbstractRector;
use Rector\VendorLocker\ParentClassMethodTypeOverrideGuard;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictArrayParamDimFetchRector\StrictArrayParamDimFetchRectorTest
 */
final class StrictArrayParamDimFetchRector extends AbstractRector
{
    public function __construct(
        private readonly ParentClassMethodTypeOverrideGuard $parentClassMethodTypeOverrideGuard
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add array type based on array dim fetch use', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function resolve($item)
    {
        return $item['name'];
    }
}
CODE_SAMPLE

                ,
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function resolve(array $item)
    {
        return $item['name'];
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
        return [ClassMethod::class, Function_::class, Closure::class];
    }

    /**
     * @param ClassMethod|Function_|Closure $node
     */
    public function refactor(Node $node): ?Node
    {
        $hasChanged = false;

        if ($node instanceof ClassMethod && $this->parentClassMethodTypeOverrideGuard->hasParentClassMethod($node)) {
            return null;
        }

        foreach ($node->getParams() as $param) {
            if ($param->type instanceof Node) {
                continue;
            }

            if (! $this->isParamAccessedArrayDimFetch($param, $node)) {
                continue;
            }

            $param->type = new Identifier('array');
            $hasChanged = true;
        }

        if ($hasChanged) {
            return $node;
        }

        return null;
    }

    private function isParamAccessedArrayDimFetch(Param $param, ClassMethod|Function_|Closure $functionLike): bool
    {
        if ($functionLike->stmts === null) {
            return false;
        }

        $paramName = $this->getName($param);

        $isParamAccessedArrayDimFetch = false;
        $this->traverseNodesWithCallable($functionLike->stmts, function (Node $node) use (
            $paramName,
            &$isParamAccessedArrayDimFetch,
        ): int|null {
            if ($node instanceof Class_ || $node instanceof FunctionLike) {
                return NodeTraverser::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
            }

            if ($this->shouldStop($node, $paramName)) {
                // force set to false to avoid too early replaced
                $isParamAccessedArrayDimFetch = false;
                return NodeTraverser::STOP_TRAVERSAL;
            }

            if (! $node instanceof ArrayDimFetch) {
                return null;
            }

            if (! $node->var instanceof Variable) {
                return null;
            }

            if (! $this->isName($node->var, $paramName)) {
                return null;
            }

            // skip possible strings
            $variableType = $this->getType($node->var);
            if ($variableType->isString()->yes()) {
                return null;
            }

            $isParamAccessedArrayDimFetch = true;
            return null;
        });

        return $isParamAccessedArrayDimFetch;
    }

    private function shouldStop(Node $node, string $paramName): bool
    {
        $nodeToCheck = null;

        if ($node instanceof FuncCall
            && ! $node->isFirstClassCallable()
            && $this->isNames($node, ['is_array', 'is_string', 'is_int', 'is_bool', 'is_float'])) {
            $firstArg = $node->getArgs()[0];
            $nodeToCheck = $firstArg->value;
        }

        if ($node instanceof Expression) {
            $nodeToCheck = $node->expr;
        }

        if ($node instanceof Coalesce) {
            $nodeToCheck = $node->left;
        }

        if ($node instanceof AssignOpCoalesce) {
            $nodeToCheck = $node->var;
        }

        if ($nodeToCheck instanceof MethodCall) {
            return $nodeToCheck->var instanceof Variable && $this->isName($nodeToCheck->var, $paramName);
        }

        if ($nodeToCheck instanceof ArrayDimFetch) {
            return $nodeToCheck->var instanceof Variable && $this->isName($nodeToCheck->var, $paramName);
        }

        return $nodeToCheck instanceof Variable && $this->isName($nodeToCheck, $paramName);
    }
}
