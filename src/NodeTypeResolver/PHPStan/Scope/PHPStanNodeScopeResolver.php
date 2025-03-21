<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\PHPStan\Scope;

use Error;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\ArrayItem;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\ArrowFunction;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\AssignOp;
use PhpParser\Node\Expr\AssignRef;
use PhpParser\Node\Expr\BinaryOp;
use PhpParser\Node\Expr\BitwiseNot;
use PhpParser\Node\Expr\BooleanNot;
use PhpParser\Node\Expr\CallLike;
use PhpParser\Node\Expr\Cast;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\Clone_;
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\Empty_;
use PhpParser\Node\Expr\ErrorSuppress;
use PhpParser\Node\Expr\Eval_;
use PhpParser\Node\Expr\Exit_;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Include_;
use PhpParser\Node\Expr\Instanceof_;
use PhpParser\Node\Expr\Isset_;
use PhpParser\Node\Expr\List_;
use PhpParser\Node\Expr\Match_;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\NullsafeMethodCall;
use PhpParser\Node\Expr\PostDec;
use PhpParser\Node\Expr\PostInc;
use PhpParser\Node\Expr\PreDec;
use PhpParser\Node\Expr\PreInc;
use PhpParser\Node\Expr\Print_;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\Expr\Ternary;
use PhpParser\Node\Expr\Throw_;
use PhpParser\Node\Expr\UnaryMinus;
use PhpParser\Node\Expr\UnaryPlus;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Expr\Yield_;
use PhpParser\Node\Expr\YieldFrom;
use PhpParser\Node\Identifier;
use PhpParser\Node\IntersectionType;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Block;
use PhpParser\Node\Stmt\Catch_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Do_;
use PhpParser\Node\Stmt\Echo_;
use PhpParser\Node\Stmt\ElseIf_;
use PhpParser\Node\Stmt\Enum_;
use PhpParser\Node\Stmt\EnumCase;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Finally_;
use PhpParser\Node\Stmt\For_;
use PhpParser\Node\Stmt\Foreach_;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\Return_;
use PhpParser\Node\Stmt\Switch_;
use PhpParser\Node\Stmt\Trait_;
use PhpParser\Node\Stmt\TryCatch;
use PhpParser\Node\Stmt\Unset_;
use PhpParser\Node\Stmt\While_;
use PhpParser\Node\UnionType;
use PhpParser\NodeTraverser;
use PHPStan\Analyser\MutatingScope;
use PHPStan\Analyser\NodeScopeResolver;
use PHPStan\Analyser\ScopeContext;
use PHPStan\Node\FunctionCallableNode;
use PHPStan\Node\InstantiationCallableNode;
use PHPStan\Node\MethodCallableNode;
use PHPStan\Node\Printer\Printer;
use PHPStan\Node\StaticMethodCallableNode;
use PHPStan\Node\UnreachableStatementNode;
use PHPStan\Node\VirtualNode;
use PHPStan\Parser\ParserErrorsException;
use PHPStan\PhpDocParser\Parser\ParserException;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\ShouldNotHappenException;
use PHPStan\Type\ObjectType;
use PHPStan\Type\TypeCombinator;
use Rector\Contract\PhpParser\Node\StmtsAwareInterface;
use Rector\NodeAnalyzer\ClassAnalyzer;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\PHPStan\Scope\Contract\NodeVisitor\ScopeResolverNodeVisitorInterface;
use Rector\Util\Reflection\PrivatesAccessor;
use Webmozart\Assert\Assert;

/**
 * @inspired by https://github.com/silverstripe/silverstripe-upgrader/blob/532182b23e854d02e0b27e68ebc394f436de0682/src/UpgradeRule/PHP/Visitor/PHPStanScopeVisitor.php
 * - https://github.com/silverstripe/silverstripe-upgrader/pull/57/commits/e5c7cfa166ad940d9d4ff69537d9f7608e992359#diff-5e0807bb3dc03d6a8d8b6ad049abd774
 */
final readonly class PHPStanNodeScopeResolver
{
    /**
     * @var string
     */
    private const CONTEXT = 'context';

    private NodeTraverser $nodeTraverser;

    /**
     * @param ScopeResolverNodeVisitorInterface[] $nodeVisitors
     */
    public function __construct(
        private NodeScopeResolver $nodeScopeResolver,
        private ReflectionProvider $reflectionProvider,
        iterable $nodeVisitors,
        private ScopeFactory $scopeFactory,
        private PrivatesAccessor $privatesAccessor,
        private NodeNameResolver $nodeNameResolver,
        private ClassAnalyzer $classAnalyzer
    ) {
        $this->nodeTraverser = new NodeTraverser(...$nodeVisitors);
    }

    /**
     * @param Stmt[] $stmts
     * @return Stmt[]
     */
    public function processNodes(
        array $stmts,
        string $filePath,
        ?MutatingScope $formerMutatingScope = null
    ): array {
        /**
         * The stmts must be array of Stmt, or it will be silently skipped by PHPStan
         * @see vendor/phpstan/phpstan/phpstan.phar/src/Analyser/NodeScopeResolver.php:282
         */

        Assert::allIsInstanceOf($stmts, Stmt::class);

        $this->nodeTraverser->traverse($stmts);

        $scope = $formerMutatingScope ?? $this->scopeFactory->createFromFile($filePath);

        $nodeCallback = function (Node $node, MutatingScope $mutatingScope) use (
            &$nodeCallback,
        ): void {
            // the class reflection is resolved AFTER entering to class node
            // so we need to get it from the first after this one
            if ($node instanceof Class_ || $node instanceof Interface_ || $node instanceof Enum_) {
                /** @var MutatingScope $mutatingScope */
                $mutatingScope = $this->resolveClassOrInterfaceScope($node, $mutatingScope);
                $node->setAttribute(AttributeKey::SCOPE, $mutatingScope);

                return;
            }

            if ($node instanceof Trait_) {
                $this->processTrait($node, $mutatingScope, $nodeCallback);

                return;
            }

            // special case for unreachable nodes
            // early check here as UnreachableStatementNode is special VirtualNode
            // so node to be checked inside
            if ($node instanceof UnreachableStatementNode) {
                $this->processUnreachableStatementNode($node, $mutatingScope, $nodeCallback);
                return;
            }

            // init current Node set Attribute
            // not a VirtualNode, then set scope attribute
            // do not return early, as its properties will be checked next
            if (! $node instanceof VirtualNode) {
                $node->setAttribute(AttributeKey::SCOPE, $mutatingScope);
            }

            // Block node has stmts, just not part of StmtsAwareInterface
            if ($node instanceof StmtsAwareInterface || $node instanceof Block) {
                $this->processStmtsAwareOrBlock($node, $mutatingScope, $nodeCallback);
                return;
            }

            $this->decorateNodeAttrGroups($node, $mutatingScope, $nodeCallback);

            if ((
                $node instanceof Expression ||
                $node instanceof Return_ ||
                $node instanceof EnumCase ||
                $node instanceof Cast ||
                $node instanceof YieldFrom ||
                $node instanceof UnaryMinus ||
                $node instanceof UnaryPlus ||
                $node instanceof Throw_ ||
                $node instanceof Empty_ ||
                $node instanceof BooleanNot ||
                $node instanceof Clone_ ||
                $node instanceof ErrorSuppress ||
                $node instanceof BitwiseNot ||
                $node instanceof Eval_ ||
                $node instanceof Print_ ||
                $node instanceof Exit_ ||
                $node instanceof ArrowFunction ||
                $node instanceof Include_ ||
                $node instanceof Instanceof_
            ) && $node->expr instanceof Expr) {
                $node->expr->setAttribute(AttributeKey::SCOPE, $mutatingScope);
                return;
            }

            if ($node instanceof PostInc ||
                $node instanceof PostDec ||
                $node instanceof PreInc ||
                $node instanceof PreDec) {
                $node->var->setAttribute(AttributeKey::SCOPE, $mutatingScope);
                return;
            }

            if ($node instanceof ArrayDimFetch) {
                $node->var->setAttribute(AttributeKey::SCOPE, $mutatingScope);
                if ($node->dim instanceof Expr) {
                    $node->dim->setAttribute(AttributeKey::SCOPE, $mutatingScope);
                }

                return;
            }

            if ($node instanceof Assign || $node instanceof AssignOp || $node instanceof AssignRef) {
                $this->processAssign($node, $mutatingScope);

                if ($node->var instanceof Variable && $node->var->name instanceof Expr) {
                    $this->nodeScopeResolverProcessNodes(
                        [new Expression($node->var), new Expression($node->expr)],
                        $mutatingScope,
                        $nodeCallback
                    );
                }

                return;
            }

            if ($node instanceof Ternary) {
                $this->processTernary($node, $mutatingScope);
                return;
            }

            if ($node instanceof BinaryOp) {
                $this->processBinaryOp($node, $mutatingScope);
                return;
            }

            if ($node instanceof Arg) {
                $node->value->setAttribute(AttributeKey::SCOPE, $mutatingScope);
                return;
            }

            if ($node instanceof Array_) {
                $this->processArray($node, $mutatingScope);
                return;
            }

            if ($node instanceof Property) {
                $this->processProperty($node, $mutatingScope, $nodeCallback);
                return;
            }

            if ($node instanceof Switch_) {
                $this->processSwitch($node, $mutatingScope);
                return;
            }

            if ($node instanceof ArrayItem) {
                $this->processArrayItem($node, $mutatingScope);
                return;
            }

            if ($node instanceof NullableType) {
                $node->type->setAttribute(AttributeKey::SCOPE, $mutatingScope);
                return;
            }

            if ($node instanceof UnionType || $node instanceof IntersectionType) {
                foreach ($node->types as $type) {
                    $type->setAttribute(AttributeKey::SCOPE, $mutatingScope);
                }

                return;
            }

            if ($node instanceof StaticPropertyFetch || $node instanceof ClassConstFetch) {
                $node->class->setAttribute(AttributeKey::SCOPE, $mutatingScope);
                $node->name->setAttribute(AttributeKey::SCOPE, $mutatingScope);
                return;
            }

            if ($node instanceof PropertyFetch) {
                $node->var->setAttribute(AttributeKey::SCOPE, $mutatingScope);
                $node->name->setAttribute(AttributeKey::SCOPE, $mutatingScope);
                return;
            }

            if ($node instanceof ConstFetch) {
                $node->name->setAttribute(AttributeKey::SCOPE, $mutatingScope);
                return;
            }

            if ($node instanceof CallLike) {
                $this->processCallike($node, $mutatingScope);
                return;
            }

            if ($node instanceof Match_) {
                $this->processMatch($node, $mutatingScope);
                return;
            }

            if ($node instanceof Yield_) {
                $this->processYield($node, $mutatingScope);
                return;
            }

            if ($node instanceof Isset_ || $node instanceof Unset_) {
                $this->processIssetOrUnset($node, $mutatingScope);
                return;
            }

            if ($node instanceof Echo_) {
                $this->processEcho($node, $mutatingScope);
                return;
            }

            if ($node instanceof MethodCallableNode || $node instanceof FunctionCallableNode || $node instanceof StaticMethodCallableNode || $node instanceof InstantiationCallableNode) {
                $node->getOriginalNode()
                    ->setAttribute(AttributeKey::SCOPE, $mutatingScope);
                $this->processCallike($node->getOriginalNode(), $mutatingScope);

                return;
            }
        };

        try {
            $this->nodeScopeResolverProcessNodes($stmts, $scope, $nodeCallback);
        } catch (Error $error) {
            if (! str_starts_with($error->getMessage(), 'Call to undefined method ' . Printer::class . '::pPHPStan_')) {
                throw $error;
            }

            // nothing we can do more precise here as error printing from deep internal PHPStan Printer service with service injection we cannot reset
            // in the middle of process
            // fallback to fill by found scope
            RectorNodeScopeResolver::processNodes($stmts, $scope);
        }

        return $stmts;
    }

    /**
     * @param callable(Node $node, MutatingScope $scope): void $nodeCallback
     */
    private function processStmtsAwareOrBlock(StmtsAwareInterface|Block $stmtsAware, MutatingScope $mutatingScope, callable $nodeCallback): void
    {
        if ($stmtsAware->stmts !== null) {
            $this->nodeScopeResolverProcessNodes($stmtsAware->stmts, $mutatingScope, $nodeCallback);
        }

        if ($stmtsAware instanceof Foreach_) {
            // decorate value as well
            $stmtsAware->valueVar->setAttribute(AttributeKey::SCOPE, $mutatingScope);
            if ($stmtsAware->valueVar instanceof List_) {
                $this->processArray($stmtsAware->valueVar, $mutatingScope);
            }

            return;
        }

        if ($stmtsAware instanceof For_) {
            foreach (array_merge($stmtsAware->init, $stmtsAware->cond, $stmtsAware->loop) as $expr) {
                $expr->setAttribute(AttributeKey::SCOPE, $mutatingScope);
                if ($expr instanceof BinaryOp) {
                    $this->processBinaryOp($expr, $mutatingScope);
                }

                if ($expr instanceof Assign) {
                    $this->processAssign($expr, $mutatingScope);
                }
            }

            return;
        }

        if ($stmtsAware instanceof TryCatch) {
            $this->processTryCatch($stmtsAware, $mutatingScope);
            return;
        }

        if ($stmtsAware instanceof Catch_) {
            $this->processCatch($stmtsAware, $mutatingScope, $nodeCallback);
            return;
        }

        if ($stmtsAware instanceof If_ || $stmtsAware instanceof ElseIf_ || $stmtsAware instanceof Do_ || $stmtsAware instanceof While_) {
            $stmtsAware->cond->setAttribute(AttributeKey::SCOPE, $mutatingScope);
            return;
        }
    }

    private function processYield(Yield_ $yield, MutatingScope $mutatingScope): void
    {
        if ($yield->key instanceof Expr) {
            $yield->key->setAttribute(AttributeKey::SCOPE, $mutatingScope);
        }

        if ($yield->value instanceof Expr) {
            $yield->value->setAttribute(AttributeKey::SCOPE, $mutatingScope);
        }
    }

    private function processIssetOrUnset(Isset_|Unset_ $node, MutatingScope $mutatingScope): void
    {
        foreach ($node->vars as $var) {
            $var->setAttribute(AttributeKey::SCOPE, $mutatingScope);
        }
    }

    private function processEcho(Echo_ $echo, MutatingScope $mutatingScope): void
    {
        foreach ($echo->exprs as $expr) {
            $expr->setAttribute(AttributeKey::SCOPE, $mutatingScope);
        }
    }

    private function processMatch(Match_ $match, MutatingScope $mutatingScope): void
    {
        $match->cond->setAttribute(AttributeKey::SCOPE, $mutatingScope);
        foreach ($match->arms as $arm) {
            if ($arm->conds !== null) {
                foreach ($arm->conds as $cond) {
                    $cond->setAttribute(AttributeKey::SCOPE, $mutatingScope);
                }
            }

            $arm->body->setAttribute(AttributeKey::SCOPE, $mutatingScope);
        }
    }

    /**
     * @param Stmt[] $stmts
     * @param callable(Node $node, MutatingScope $scope): void $nodeCallback
     */
    private function nodeScopeResolverProcessNodes(
        array $stmts,
        MutatingScope $mutatingScope,
        callable $nodeCallback
    ): void {
        try {
            $this->nodeScopeResolver->processNodes($stmts, $mutatingScope, $nodeCallback);
        } catch (ParserErrorsException|ParserException|ShouldNotHappenException) {
            // nothing we can do more precise here as error parsing from deep internal PHPStan service with service injection we cannot reset
            // in the middle of process
            // fallback to fill by found scope
            RectorNodeScopeResolver::processNodes($stmts, $mutatingScope);
        }
    }

    private function processCallike(CallLike $callLike, MutatingScope $mutatingScope): void
    {
        if ($callLike instanceof StaticCall) {
            $callLike->class->setAttribute(AttributeKey::SCOPE, $mutatingScope);
            $callLike->name->setAttribute(AttributeKey::SCOPE, $mutatingScope);
        } elseif ($callLike instanceof MethodCall || $callLike instanceof NullsafeMethodCall) {
            $callLike->var->setAttribute(AttributeKey::SCOPE, $mutatingScope);
            $callLike->name->setAttribute(AttributeKey::SCOPE, $mutatingScope);
        } elseif ($callLike instanceof FuncCall) {
            $callLike->name->setAttribute(AttributeKey::SCOPE, $mutatingScope);
        } elseif ($callLike instanceof New_ && ! $callLike->class instanceof Class_) {
            $callLike->class->setAttribute(AttributeKey::SCOPE, $mutatingScope);
        }
    }

    private function processAssign(Assign|AssignOp|AssignRef $assign, MutatingScope $mutatingScope): void
    {
        $assign->var->setAttribute(AttributeKey::SCOPE, $mutatingScope);
        $assign->expr->setAttribute(AttributeKey::SCOPE, $mutatingScope);
    }

    private function processArray(List_|Array_ $array, MutatingScope $mutatingScope): void
    {
        foreach ($array->items as $arrayItem) {
            if ($arrayItem instanceof ArrayItem) {
                $this->processArrayItem($arrayItem, $mutatingScope);
            }
        }
    }

    private function processArrayItem(ArrayItem $arrayItem, MutatingScope $mutatingScope): void
    {
        if ($arrayItem->key instanceof Expr) {
            $arrayItem->key->setAttribute(AttributeKey::SCOPE, $mutatingScope);
        }

        $arrayItem->value->setAttribute(AttributeKey::SCOPE, $mutatingScope);
    }

    /**
     * @param callable(Node $trait, MutatingScope $scope): void $nodeCallback
     */
    private function decorateNodeAttrGroups(Node $node, MutatingScope $mutatingScope, callable $nodeCallback): void
    {
        // better to have AttrGroupsAwareInterface for all Node definition with attrGroups property
        // but because may conflict with StmtsAwareInterface patch, this needs to be here
        if (
            ! $node instanceof Param &&
            ! $node instanceof ArrowFunction &&
            ! $node instanceof Closure &&
            ! $node instanceof ClassConst &&
            ! $node instanceof ClassLike &&
            ! $node instanceof ClassMethod &&
            ! $node instanceof EnumCase &&
            ! $node instanceof Function_ &&
            ! $node instanceof Property
        ) {
            return;
        }

        foreach ($node->attrGroups as $attrGroup) {
            foreach ($attrGroup->attrs as $attr) {
                foreach ($attr->args as $arg) {
                    $this->nodeScopeResolverProcessNodes(
                        [new Expression($arg->value)],
                        $mutatingScope,
                        $nodeCallback
                    );
                }
            }
        }
    }

    private function processSwitch(Switch_ $switch, MutatingScope $mutatingScope): void
    {
        $switch->cond->setAttribute(AttributeKey::SCOPE, $mutatingScope);

        // decorate value as well
        foreach ($switch->cases as $case) {
            $case->setAttribute(AttributeKey::SCOPE, $mutatingScope);
        }
    }

    /**
     * @param callable(Node $node, MutatingScope $scope): void $nodeCallback
     */
    private function processCatch(Catch_ $catch, MutatingScope $mutatingScope, callable $nodeCallback): void
    {
        $varName = $catch->var instanceof Variable
            ? $this->nodeNameResolver->getName($catch->var)
            : null;

        $type = TypeCombinator::union(
            ...array_map(static fn (Name $name): ObjectType => new ObjectType((string) $name), $catch->types)
        );

        $catchMutatingScope = $mutatingScope->enterCatchType($type, $varName);
        $this->nodeScopeResolverProcessNodes($catch->stmts, $catchMutatingScope, $nodeCallback);
    }

    private function processTryCatch(TryCatch $tryCatch, MutatingScope $mutatingScope): void
    {
        if ($tryCatch->finally instanceof Finally_) {
            $tryCatch->finally->setAttribute(AttributeKey::SCOPE, $mutatingScope);
        }
    }

    /**
     * @param callable(Node $node, MutatingScope $scope): void $nodeCallback
     */
    private function processUnreachableStatementNode(
        UnreachableStatementNode $unreachableStatementNode,
        MutatingScope $mutatingScope,
        callable $nodeCallback
    ): void {
        $originalStmt = $unreachableStatementNode->getOriginalStatement();

        $this->nodeScopeResolverProcessNodes(
            array_merge([$originalStmt], $unreachableStatementNode->getNextStatements()),
            $mutatingScope,
            $nodeCallback
        );
    }

    /**
     * @param callable(Node $node, MutatingScope $scope): void $nodeCallback
     */
    private function processProperty(Property $property, MutatingScope $mutatingScope, callable $nodeCallback): void
    {
        foreach ($property->props as $propertyProperty) {
            $propertyProperty->setAttribute(AttributeKey::SCOPE, $mutatingScope);

            if ($propertyProperty->default instanceof Expr) {
                $propertyProperty->default->setAttribute(AttributeKey::SCOPE, $mutatingScope);
            }
        }

        foreach ($property->hooks as $hook) {
            if ($hook->body === null) {
                continue;
            }

            /** @var Stmt[] $stmts */
            $stmts = $hook->body instanceof Expr
                ? [new Expression($hook->body)]
                : [$hook->body];
            $this->nodeScopeResolverProcessNodes($stmts, $mutatingScope, $nodeCallback);
        }
    }

    private function processBinaryOp(BinaryOp $binaryOp, MutatingScope $mutatingScope): void
    {
        $binaryOp->left->setAttribute(AttributeKey::SCOPE, $mutatingScope);
        $binaryOp->right->setAttribute(AttributeKey::SCOPE, $mutatingScope);
    }

    private function processTernary(Ternary $ternary, MutatingScope $mutatingScope): void
    {
        if ($ternary->if instanceof Expr) {
            $ternary->if->setAttribute(AttributeKey::SCOPE, $mutatingScope);
        }

        $ternary->else->setAttribute(AttributeKey::SCOPE, $mutatingScope);
    }

    private function resolveClassOrInterfaceScope(
        Class_ | Interface_ | Enum_ $classLike,
        MutatingScope $mutatingScope
    ): MutatingScope {
        $isAnonymous = $this->classAnalyzer->isAnonymousClass($classLike);

        // is anonymous class? - not possible to enter it since PHPStan 0.12.33, see https://github.com/phpstan/phpstan-src/commit/e87fb0ec26f9c8552bbeef26a868b1e5d8185e91
        if ($classLike instanceof Class_ && $isAnonymous) {
            $classReflection = $this->reflectionProvider->getAnonymousClassReflection($classLike, $mutatingScope);
        } else {
            $className = $this->resolveClassName($classLike);
            if (! $this->reflectionProvider->hasClass($className)) {
                return $mutatingScope;
            }

            $classReflection = $this->reflectionProvider->getClass($className);
        }

        try {
            return $mutatingScope->enterClass($classReflection);
        } catch (ShouldNotHappenException) {
        }

        $context = $this->privatesAccessor->getPrivateProperty($mutatingScope, 'context');
        $this->privatesAccessor->setPrivateProperty($context, 'classReflection', null);

        try {
            return $mutatingScope->enterClass($classReflection);
        } catch (ShouldNotHappenException) {
        }

        return $mutatingScope;
    }

    private function resolveClassName(Class_ | Interface_ | Trait_| Enum_ $classLike): string
    {
        if ($classLike->namespacedName instanceof Name) {
            return (string) $classLike->namespacedName;
        }

        if (! $classLike->name instanceof Identifier) {
            return '';
        }

        return $classLike->name->toString();
    }

    /**
     * @param callable(Node $trait, MutatingScope $scope): void $nodeCallback
     */
    private function processTrait(Trait_ $trait, MutatingScope $mutatingScope, callable $nodeCallback): void
    {
        $traitName = $this->resolveClassName($trait);

        if (! $this->reflectionProvider->hasClass($traitName)) {
            $trait->setAttribute(AttributeKey::SCOPE, $mutatingScope);
            $this->nodeScopeResolverProcessNodes($trait->stmts, $mutatingScope, $nodeCallback);
            $this->decorateNodeAttrGroups($trait, $mutatingScope, $nodeCallback);

            return;
        }

        $traitClassReflection = $this->reflectionProvider->getClass($traitName);

        $traitScope = clone $mutatingScope;

        /** @var ScopeContext $scopeContext */
        $scopeContext = $this->privatesAccessor->getPrivateProperty($traitScope, self::CONTEXT);
        $traitContext = clone $scopeContext;

        // before entering the class/trait again, we have to tell scope no class was set, otherwise it crashes
        $this->privatesAccessor->setPrivateProperty($traitContext, 'classReflection', $traitClassReflection);

        $this->privatesAccessor->setPrivateProperty($traitScope, self::CONTEXT, $traitContext);

        $trait->setAttribute(AttributeKey::SCOPE, $traitScope);
        $this->nodeScopeResolverProcessNodes($trait->stmts, $traitScope, $nodeCallback);
        $this->decorateNodeAttrGroups($trait, $traitScope, $nodeCallback);
    }
}
