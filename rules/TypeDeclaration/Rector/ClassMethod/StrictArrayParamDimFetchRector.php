<?php

declare(strict_types=1);

namespace Rector\TypeDeclaration\Rector\ClassMethod;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\AssignOp\Coalesce as AssignOpCoalesce;
use PhpParser\Node\Expr\BinaryOp\Coalesce;
use PhpParser\Node\Expr\CallLike;
use PhpParser\Node\Expr\Cast\Array_;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\Empty_;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Instanceof_;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Echo_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Function_;
use PhpParser\NodeVisitor;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;
use Rector\NodeTypeResolver\PHPStan\Type\TypeFactory;
use Rector\NodeTypeResolver\TypeComparator\TypeComparator;
use Rector\Rector\AbstractRector;
use Rector\VendorLocker\ParentClassMethodTypeOverrideGuard;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictArrayParamDimFetchRector\StrictArrayParamDimFetchRectorTest
 */
final class StrictArrayParamDimFetchRector extends AbstractRector
{
    public function __construct(
        private readonly ParentClassMethodTypeOverrideGuard $parentClassMethodTypeOverrideGuard,
        private readonly TypeComparator $typeComparator,
        private readonly TypeFactory $typeFactory
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

        if ($node instanceof ClassMethod && $this->parentClassMethodTypeOverrideGuard->isTypeGuardedClass($node)) {
            return null;
        }

        foreach ($node->getParams() as $param) {
            if ($param->type instanceof Node) {
                continue;
            }

            if ($param->variadic) {
                continue;
            }

            if ($param->default instanceof Expr && ! $this->getType($param->default)->isArray()->yes()) {
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

        if ($this->isDimFetchResultFedToSelfCall($functionLike, $paramName)) {
            return false;
        }

        $isParamAccessedArrayDimFetch = false;
        $this->traverseNodesWithCallable($functionLike->stmts, function (Node $node) use (
            $paramName,
            &$isParamAccessedArrayDimFetch,
        ): int|null {
            if ($node instanceof Class_ || $node instanceof FunctionLike) {
                return NodeVisitor::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
            }

            if ($this->shouldStop($node, $paramName)) {
                // force set to false to avoid too early replaced
                $isParamAccessedArrayDimFetch = false;
                return NodeVisitor::STOP_TRAVERSAL;
            }

            if (! $node instanceof ArrayDimFetch) {
                return null;
            }

            if (! $node->dim instanceof Expr) {
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
                // force set to false to avoid too early replaced
                $isParamAccessedArrayDimFetch = false;
                return NodeVisitor::STOP_TRAVERSAL;
            }

            // skip integer in possibly string type as string can be accessed via int
            $dimType = $this->getType($node->dim);
            if ($dimType->isInteger()->yes() && $variableType->isString()->maybe()) {
                return null;
            }

            $variableType = $this->typeFactory->createMixedPassedOrUnionType([$variableType]);
            if ($variableType instanceof UnionType) {
                $isParamAccessedArrayDimFetch = false;
                return NodeVisitor::STOP_TRAVERSAL;
            }

            if ($this->isArrayAccess($variableType)) {
                $isParamAccessedArrayDimFetch = false;
                return NodeVisitor::STOP_TRAVERSAL;
            }

            $isParamAccessedArrayDimFetch = true;
            return null;
        });

        return $isParamAccessedArrayDimFetch;
    }

    /**
     * Skip when a value read from $param[dim] is fed back as an argument into the same
     * closure/function (recursion). That element-as-container use signals an ArrayAccess
     * tree (e.g. Symfony FormView), not a plain array.
     */
    private function isDimFetchResultFedToSelfCall(
        ClassMethod|Function_|Closure $functionLike,
        string $paramName
    ): bool {
        if ($functionLike->stmts === null) {
            return false;
        }

        $selfCallNames = $this->resolveSelfCallNames($functionLike);
        if ($selfCallNames === []) {
            return false;
        }

        // collect variables assigned from $param[dim]
        $dimFetchedVariableNames = [];
        $this->traverseNodesWithCallable($functionLike->stmts, function (Node $node) use (
            $paramName,
            &$dimFetchedVariableNames
        ): null {
            if ($node instanceof Assign
                && $node->var instanceof Variable
                && $node->expr instanceof ArrayDimFetch
                && $node->expr->var instanceof Variable
                && $this->isName($node->expr->var, $paramName)) {
                $dimFetchedVariableNames[] = $this->getName($node->var);
            }

            return null;
        });

        $isFed = false;
        $this->traverseNodesWithCallable($functionLike->stmts, function (Node $node) use (
            $selfCallNames,
            $dimFetchedVariableNames,
            $paramName,
            &$isFed
        ): null {
            if ($isFed) {
                return null;
            }

            if (! $node instanceof FuncCall || $node->isFirstClassCallable()) {
                return null;
            }

            if (! $node->name instanceof Variable && ! $node->name instanceof Name) {
                return null;
            }

            if (! in_array($this->getName($node->name), $selfCallNames, true)) {
                return null;
            }

            foreach ($node->getArgs() as $arg) {
                if ($arg->value instanceof Variable
                    && in_array($this->getName($arg->value), $dimFetchedVariableNames, true)) {
                    $isFed = true;
                    return null;
                }

                if ($arg->value instanceof ArrayDimFetch
                    && $arg->value->var instanceof Variable
                    && $this->isName($arg->value->var, $paramName)) {
                    $isFed = true;
                    return null;
                }
            }

            return null;
        });

        return $isFed;
    }

    /**
     * @return string[]
     */
    private function resolveSelfCallNames(ClassMethod|Function_|Closure $functionLike): array
    {
        if ($functionLike instanceof Closure) {
            $selfCallNames = [];
            foreach ($functionLike->uses as $use) {
                if ($use->byRef) {
                    $useName = $this->getName($use->var);
                    if ($useName !== null) {
                        $selfCallNames[] = $useName;
                    }
                }
            }

            return $selfCallNames;
        }

        if ($functionLike instanceof Function_) {
            return [$functionLike->name->toString()];
        }

        return [];
    }

    private function isEchoed(Node $node, string $paramName): bool
    {
        if (! $node instanceof Echo_) {
            return false;
        }

        return array_any(
            $node->exprs,
            fn (Expr $expr): bool => $expr instanceof Variable && $this->isName($expr, $paramName)
        );
    }

    private function shouldStop(Node $node, string $paramName): bool
    {
        $nodeToCheck = null;

        if ($node instanceof FuncCall && ! $node->isFirstClassCallable()
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

        if ($this->isMethodCall($paramName, $nodeToCheck)) {
            return true;
        }

        if ($nodeToCheck instanceof Variable && $this->isName($nodeToCheck, $paramName)) {
            return true;
        }

        if ($this->isEmptyOrEchoedOrCasted($node, $paramName)) {
            return true;
        }

        if ($this->isPropertyFetchedOnArrayDimFetch($node, $paramName)) {
            return true;
        }

        if ($this->isInstanceofParam($node, $paramName)) {
            return true;
        }

        return $this->isReassignAndUseAsArg($node, $paramName);
    }

    private function isReassignAndUseAsArg(Node $node, string $paramName): bool
    {
        if (! $node instanceof Assign) {
            return false;
        }

        if (! $node->var instanceof Variable) {
            return false;
        }

        if (! $this->isName($node->var, $paramName)) {
            return false;
        }

        if (! $node->expr instanceof CallLike) {
            return false;
        }

        if ($node->expr->isFirstClassCallable()) {
            return false;
        }

        return array_any(
            $node->expr->getArgs(),
            fn (Arg $arg): bool => $arg->value instanceof Variable && $this->isName($arg->value, $paramName)
        );
    }

    private function isEmptyOrEchoedOrCasted(Node $node, string $paramName): bool
    {
        if ($node instanceof Empty_ && $node->expr instanceof Variable && $this->isName($node->expr, $paramName)) {
            return true;
        }

        if ($this->isEchoed($node, $paramName)) {
            return true;
        }

        return $node instanceof Array_ && $node->expr instanceof Variable && $this->isName($node->expr, $paramName);
    }

    private function isPropertyFetchedOnArrayDimFetch(Node $node, string $paramName): bool
    {
        if (! $node instanceof PropertyFetch && ! $node instanceof StaticPropertyFetch) {
            return false;
        }

        $fetchedOn = $node instanceof PropertyFetch ? $node->var : $node->class;
        if (! $fetchedOn instanceof ArrayDimFetch) {
            return false;
        }

        return $fetchedOn->var instanceof Variable && $this->isName($fetchedOn->var, $paramName);
    }

    private function isInstanceofParam(Node $node, string $paramName): bool
    {
        return $node instanceof Instanceof_ && $node->expr instanceof Variable && $this->isName(
            $node->expr,
            $paramName
        );
    }

    private function isMethodCall(string $paramName, ?Node $node): bool
    {
        if ($node instanceof MethodCall) {
            return $node->var instanceof Variable && $this->isName($node->var, $paramName);
        }

        return false;
    }

    private function isArrayAccess(Type $type): bool
    {
        if (! $type instanceof ObjectType) {
            return false;
        }

        return $this->typeComparator->isSubtype($type, new ObjectType('ArrayAccess'));
    }
}
