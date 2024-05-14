<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\AssignOp\Concat;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Identifier;
use PhpParser\Node\NullableType;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\NodeTraverser;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Rector\AbstractRector;
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
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
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
        return [ClassMethod::class, Function_::class, Closure::class];
    }

    /**
     * @param ClassMethod|Function_|Closure $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node instanceof ClassMethod && $this->parentClassMethodTypeOverrideGuard->hasParentClassMethod($node)) {
            return null;
        }

        $hasChanged = false;
        $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);

        foreach ($node->getParams() as $param) {
            if ($param->type instanceof Node) {
                continue;
            }

            $variableConcattedFromParam = $this->resolveVariableConcattedFromParam($param, $node);
            if (! $variableConcattedFromParam instanceof Variable) {
                continue;
            }

            $paramDocType = $phpDocInfo->getParamType($this->getName($param));
            if (! $paramDocType instanceof MixedType && ! $paramDocType->isString()->yes()) {
                continue;
            }

            $nativeType = $this->nodeTypeResolver->getNativeType($variableConcattedFromParam);
            if (! $nativeType instanceof MixedType) {
                continue;
            }

            $subtractedType = $nativeType->getSubtractedType();
            if (! $subtractedType instanceof Type) {
                $param->type = new Identifier('string');
                $hasChanged = true;

                continue;
            }

            if (TypeCombinator::containsNull($subtractedType)) {
                $param->type = new NullableType(new Identifier('string'));
                $hasChanged = true;
            }
        }

        if ($hasChanged) {
            return $node;
        }

        return null;
    }

    private function resolveVariableConcattedFromParam(
        Param $param,
        ClassMethod|Function_|Closure $functionLike
    ): ?Variable {
        if ($functionLike->stmts === null) {
            return null;
        }

        if ($param->default instanceof Expr && ! $this->getType($param->default)->isString()->yes()) {
            return null;
        }

        $paramName = $this->getName($param);
        $variableConcatted = null;

        $this->traverseNodesWithCallable($functionLike->stmts, function (Node $node) use (
            $paramName,
            &$variableConcatted,
        ): int|null {
            // skip nested class and function nodes
            if ($node instanceof FunctionLike || $node instanceof Class_) {
                return NodeTraverser::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
            }

            if ($node instanceof Assign && $node->var instanceof Variable && $this->isName($node->var, $paramName)) {
                $variableConcatted = null;
                return NodeTraverser::STOP_TRAVERSAL;
            }

            $expr = $this->resolveAssignConcatVariable($node, $paramName);
            if ($expr instanceof Variable) {
                $variableConcatted = $expr;
            }

            $variableBinaryConcat = $this->resolveBinaryConcatVariable($node, $paramName);
            if ($variableBinaryConcat instanceof Variable) {
                $variableConcatted = $variableBinaryConcat;
            }

            return null;
        });

        return $variableConcatted;
    }

    private function isVariableWithSameParam(Expr $expr, string $paramName): bool
    {
        if (! $expr instanceof Variable) {
            return false;
        }

        return $this->isName($expr, $paramName);
    }

    private function resolveAssignConcatVariable(Node $node, string $paramName): ?Expr
    {
        if (! $node instanceof Concat) {
            return null;
        }

        if ($this->isVariableWithSameParam($node->var, $paramName)) {
            return $node->var;
        }

        if ($this->isVariableWithSameParam($node->expr, $paramName)) {
            return $node->expr;
        }

        return null;
    }

    private function resolveBinaryConcatVariable(Node $node, string $paramName): ?Expr
    {
        if (! $node instanceof Expr\BinaryOp\Concat) {
            return null;
        }

        if ($this->isVariableWithSameParam($node->left, $paramName)) {
            return $node->left;
        }

        if ($this->isVariableWithSameParam($node->right, $paramName)) {
            return $node->right;
        }

        return null;
    }
}
