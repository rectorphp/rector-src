<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\AssignOp\Concat;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\NodeTraverser;
use Rector\Core\Rector\AbstractRector;
use Rector\VendorLocker\ParentClassMethodTypeOverrideGuard;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictStringParamConcatRector\StrictStringParamConcatRectorTest
 */
final class StrictStringParamConcatRector extends AbstractRector
{
    public function __construct(
        private readonly ParentClassMethodTypeOverrideGuard $parentClassMethodTypeOverrideGuard,
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add string type based on concat use', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function resolve($item)
    {
        return $item . ' world';
    }
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function resolve(string $item)
    {
        return $item . ' world';
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
        return [ClassMethod::class, Function_::class];
    }

    /**
     * @param ClassMethod|Function_ $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node instanceof ClassMethod && $this->parentClassMethodTypeOverrideGuard->hasParentClassMethod($node)) {
            return null;
        }

        $hasChanged = false;

        foreach ($node->getParams() as $param) {
            if ($param->type instanceof Node) {
                continue;
            }

            if (! $this->isParamConcatted($param, $node)) {
                continue;
            }

            $param->type = new Identifier('string');
            $hasChanged = true;
        }

        if ($hasChanged) {
            return $node;
        }

        return null;
    }

    private function isParamConcatted(Param $param, ClassMethod|Function_ $functionLike): bool
    {
        $paramName = $this->getName($param);
        $isParamConcatted = false;

        $this->traverseNodesWithCallable($functionLike, function (Node $node) use (
            $paramName,
            &$isParamConcatted,
        ): int|null {
            // skip nested class and function nodes
            if ($node instanceof Closure || $node instanceof Class_) {
                return NodeTraverser::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
            }

            if ($node instanceof Concat) {
                if ($this->isVariableWithSameParam($node->var, $paramName)) {
                    $isParamConcatted = true;
                }

                if ($this->isVariableWithSameParam($node->expr, $paramName)) {
                    $isParamConcatted = true;
                }

                return null;
            }

            if ($node instanceof Expr\BinaryOp\Concat) {
                if ($this->isVariableWithSameParam($node->left, $paramName)) {
                    $isParamConcatted = true;
                }

                if ($this->isVariableWithSameParam($node->right, $paramName)) {
                    $isParamConcatted = true;
                }

                return null;
            }

            return null;
        });

        return $isParamConcatted;
    }

    private function isVariableWithSameParam(Expr $expr, string $paramName): bool
    {
        if (! $expr instanceof Variable) {
            return false;
        }

        return $this->isName($expr, $paramName);
    }
}
