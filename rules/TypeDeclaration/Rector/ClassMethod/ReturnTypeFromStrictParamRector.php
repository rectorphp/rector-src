<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Return_;
use PhpParser\NodeTraverser;
use PHPStan\Analyser\Scope;
use PHPStan\Type\MixedType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\TypeCombinator;
use PHPStan\Type\UnionType;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\Rector\AbstractScopeAwareRector;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\TypeDeclaration\TypeInferer\ReturnTypeInferer;
use Rector\VendorLocker\ParentClassMethodTypeOverrideGuard;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictParamRector\ReturnTypeFromStrictParamRectorTest
 */
final class ReturnTypeFromStrictParamRector extends AbstractScopeAwareRector implements MinPhpVersionInterface
{
    public function __construct(
        private readonly ParentClassMethodTypeOverrideGuard $parentClassMethodTypeOverrideGuard,
        private readonly ReturnTypeInferer $returnTypeInferer
    ) {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Add return type based on strict parameter type', [
            new CodeSample(
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function resolve(ParamType $item)
    {
        return $item;
    }
}
CODE_SAMPLE

                ,
                <<<'CODE_SAMPLE'
class SomeClass
{
    public function resolve(ParamType $item): ParamType
    {
        return $item;
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

    public function provideMinPhpVersion(): int
    {
        return PhpVersionFeature::NULLABLE_TYPE;
    }

    /**
     * @param ClassMethod|Function_ $node
     */
    public function refactorWithScope(Node $node, Scope $scope): ?Node
    {
        if ($this->shouldSkipNode($node)) {
            return null;
        }

        $return = $this->findCurrentScopeReturn($node);
        if ($return === null || $return->expr === null) {
            return null;
        }

        $returnName = $this->getName($return->expr);
        foreach ($node->getParams() as $param) {
            if (!$param->type instanceof Node) {
                continue;
            }

            if ($this->shouldSkipParam($param, $node)) {
                continue;
            }

            $paramName = $this->getName($param);
            if ($returnName !== $paramName) {
                continue;
            }

            $node->returnType = $param->type;
            return $node;
        }

        return null;
    }

    private function findCurrentScopeReturn(ClassMethod|Function_ $node): ?Return_
    {
        $return = null;

        $this->traverseNodesWithCallable($node->getStmts(), function (Node $node) use (&$return): ?int {
            if (! $node instanceof Return_) {
                return null;
            }

            // skip scope nesting
            if ($node instanceof FunctionLike) {
                $return = null;
                return NodeTraverser::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
            }

            if (! $node->expr instanceof Variable) {
                $return = null;
                return NodeTraverser::STOP_TRAVERSAL;
            }

            $return = $node;
            return null;
        });

        return $return;
    }

    private function shouldSkipParam(Param $param, ClassMethod|Function_ $functionLike): bool
    {
        $paramName = $this->getName($param);

        $isParamModified = false;

        $this->traverseNodesWithCallable($functionLike->getStmts(), function (Node $node) use (
            $paramName,
            &$isParamModified
        ): int|null {
            if ($node instanceof Expr\AssignRef) {
                if ($this->isName($node->expr, $paramName)) {
                    $isParamModified = true;
                    return NodeTraverser::STOP_TRAVERSAL;
                }
            }

            if (! $node instanceof Expr\Assign) {
                return null;
            }

            if (! $node->var instanceof Variable) {
                return null;
            }

            if (! $this->isName($node->var, $paramName)) {
                return null;
            }

            $isParamModified = true;
            return NodeTraverser::STOP_TRAVERSAL;
        });

        return $isParamModified;
    }

    private function shouldSkipNode(ClassMethod|Function_ $node): bool
    {
        if ($node->returnType !== null) {
            return true;
        }

        if ($node->stmts === null) {
            return true;
        }

        if ($node instanceof ClassMethod) {
            if ($this->parentClassMethodTypeOverrideGuard->hasParentClassMethod($node)) {
                return true;
            }

            if ($node->isMagic()) {
                return true;
            }
        }

        $returnType = $this->returnTypeInferer->inferFunctionLike($node);
        if ($returnType instanceof MixedType) {
            return true;
        }

        $returnType = TypeCombinator::removeNull($returnType);
        if ($returnType instanceof UnionType) {
            return true;
        }

        return false;
    }
}
