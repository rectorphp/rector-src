<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\PHPStan\Scope;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\AssignOp;
use PhpParser\Node\Expr\BinaryOp;
use PhpParser\Node\Expr\CallLike;
use PhpParser\Node\Expr\Cast;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\NullsafeMethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\Expr\Ternary;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\IntersectionType;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Catch_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Enum_;
use PhpParser\Node\Stmt\EnumCase;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Finally_;
use PhpParser\Node\Stmt\Foreach_;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\Return_;
use PhpParser\Node\Stmt\Switch_;
use PhpParser\Node\Stmt\Trait_;
use PhpParser\Node\Stmt\TryCatch;
use PhpParser\Node\UnionType;
use PhpParser\NodeTraverser;
use PHPStan\Analyser\MutatingScope;
use PHPStan\Analyser\NodeScopeResolver;
use PHPStan\Analyser\ScopeContext;
use PHPStan\Node\UnreachableStatementNode;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\ObjectType;
use PHPStan\Type\TypeCombinator;
use Rector\Exception\ShouldNotHappenException;
use Rector\NodeAnalyzer\ClassAnalyzer;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\PHPStan\Scope\Contract\NodeVisitor\ScopeResolverNodeVisitorInterface;
use Rector\PhpParser\Node\CustomNode\FileWithoutNamespace;
use Rector\PHPStan\NodeVisitor\ExprScopeFromStmtNodeVisitor;
use Rector\PHPStan\NodeVisitor\WrappedNodeRestoringNodeVisitor;
use Rector\Util\Reflection\PrivatesAccessor;
use Throwable;
use Webmozart\Assert\Assert;

/**
 * @inspired by https://github.com/silverstripe/silverstripe-upgrader/blob/532182b23e854d02e0b27e68ebc394f436de0682/src/UpgradeRule/PHP/Visitor/PHPStanScopeVisitor.php
 * - https://github.com/silverstripe/silverstripe-upgrader/pull/57/commits/e5c7cfa166ad940d9d4ff69537d9f7608e992359#diff-5e0807bb3dc03d6a8d8b6ad049abd774
 */
final class PHPStanNodeScopeResolver
{
    /**
     * @var string
     */
    private const CONTEXT = 'context';

    private readonly NodeTraverser $nodeTraverser;

    private bool $hasUnreachableStatementNode = false;

    /**
     * @param ScopeResolverNodeVisitorInterface[] $nodeVisitors
     */
    public function __construct(
        private readonly NodeScopeResolver $nodeScopeResolver,
        private readonly ReflectionProvider $reflectionProvider,
        iterable $nodeVisitors,
        private readonly ScopeFactory $scopeFactory,
        private readonly PrivatesAccessor $privatesAccessor,
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly ClassAnalyzer $classAnalyzer
    ) {
        $this->nodeTraverser = new NodeTraverser();

        foreach ($nodeVisitors as $nodeVisitor) {
            $this->nodeTraverser->addVisitor($nodeVisitor);
        }
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

        // skip chain method calls, performance issue: https://github.com/phpstan/phpstan/issues/254
        $nodeCallback = function (Node $node, MutatingScope $mutatingScope) use (&$nodeCallback, $filePath): void {
            if ($node instanceof FileWithoutNamespace) {
                $node->setAttribute(AttributeKey::SCOPE, $mutatingScope);
                $this->nodeScopeResolverProcessNodes($node->stmts, $mutatingScope, $nodeCallback);

                return;
            }

            if ((
                $node instanceof Expression ||
                $node instanceof Return_ ||
                $node instanceof EnumCase ||
                $node instanceof Cast
            ) && $node->expr instanceof Expr) {
                $node->expr->setAttribute(AttributeKey::SCOPE, $mutatingScope);
            } elseif ($node instanceof Assign || $node instanceof AssignOp) {
                $this->processAssign($node, $mutatingScope);
            } elseif ($node instanceof Ternary) {
                $this->processTernary($node, $mutatingScope);
            } elseif ($node instanceof BinaryOp) {
                $this->processBinaryOp($node, $mutatingScope);
            } elseif ($node instanceof Arg) {
                $node->value->setAttribute(AttributeKey::SCOPE, $mutatingScope);
            } elseif ($node instanceof Foreach_) {
                // decorate value as well
                $node->valueVar->setAttribute(AttributeKey::SCOPE, $mutatingScope);
                if ($node->valueVar instanceof Array_) {
                    $this->processArray($node->valueVar, $mutatingScope);
                }
            } elseif ($node instanceof Array_) {
                $this->processArray($node, $mutatingScope);
            } elseif ($node instanceof Property) {
                $this->processProperty($node, $mutatingScope);
            } elseif ($node instanceof Switch_) {
                $this->processSwitch($node, $mutatingScope);
            } elseif ($node instanceof TryCatch) {
                $this->processTryCatch($node, $mutatingScope);
            } elseif ($node instanceof Catch_) {
                $this->processCatch($node, $filePath, $mutatingScope);
            } elseif ($node instanceof ArrayItem) {
                $this->processArrayItem($node, $mutatingScope);
            } elseif ($node instanceof NullableType) {
                $node->type->setAttribute(AttributeKey::SCOPE, $mutatingScope);
            } elseif ($node instanceof UnionType || $node instanceof IntersectionType) {
                foreach ($node->types as $type) {
                    $type->setAttribute(AttributeKey::SCOPE, $mutatingScope);
                }
            } elseif ($node instanceof StaticPropertyFetch || $node instanceof ClassConstFetch) {
                $node->class->setAttribute(AttributeKey::SCOPE, $mutatingScope);
                $node->name->setAttribute(AttributeKey::SCOPE, $mutatingScope);
            } elseif ($node instanceof PropertyFetch) {
                $node->var->setAttribute(AttributeKey::SCOPE, $mutatingScope);
                $node->name->setAttribute(AttributeKey::SCOPE, $mutatingScope);
            } elseif ($node instanceof ConstFetch) {
                $node->name->setAttribute(AttributeKey::SCOPE, $mutatingScope);
            } elseif ($node instanceof CallLike) {
                $this->processCallike($node, $mutatingScope);
            }

            if ($node instanceof Trait_) {
                $this->processTrait($node, $mutatingScope, $nodeCallback);
                return;
            }

            // the class reflection is resolved AFTER entering to class node
            // so we need to get it from the first after this one
            if ($node instanceof Class_ || $node instanceof Interface_ || $node instanceof Enum_) {
                /** @var MutatingScope $mutatingScope */
                $mutatingScope = $this->resolveClassOrInterfaceScope($node, $mutatingScope);
            }

            // special case for unreachable nodes
            if ($node instanceof UnreachableStatementNode) {
                $this->processUnreachableStatementNode($node, $filePath, $mutatingScope);
            } else {
                $node->setAttribute(AttributeKey::SCOPE, $mutatingScope);
            }
        };

        $this->nodeScopeResolverProcessNodes($stmts, $scope, $nodeCallback);

        $nodeTraverser = new NodeTraverser();
        $nodeTraverser->addVisitor(new WrappedNodeRestoringNodeVisitor());
        $nodeTraverser->addVisitor(new ExprScopeFromStmtNodeVisitor($this, $filePath, $scope));
        $nodeTraverser->traverse($stmts);

        return $stmts;
    }

    public function hasUnreachableStatementNode(): bool
    {
        return $this->hasUnreachableStatementNode;
    }

    public function resetHasUnreachableStatementNode(): void
    {
        $this->hasUnreachableStatementNode = false;
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
        } catch (Throwable $throwable) {
            if ($throwable->getMessage() !== 'Internal error.') {
                throw $throwable;
            }
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

    private function processAssign(Assign|AssignOp $assign, MutatingScope $mutatingScope): void
    {
        $assign->var->setAttribute(AttributeKey::SCOPE, $mutatingScope);
        $assign->expr->setAttribute(AttributeKey::SCOPE, $mutatingScope);
    }

    private function processArray(Array_ $array, MutatingScope $mutatingScope): void
    {
        foreach ($array->items as $arrayItem) {
            if ($arrayItem instanceof ArrayItem) {
                $arrayItem->setAttribute(AttributeKey::SCOPE, $mutatingScope);
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

    private function decorateTraitAttrGroups(Trait_ $trait, MutatingScope $mutatingScope): void
    {
        foreach ($trait->attrGroups as $attrGroup) {
            foreach ($attrGroup->attrs as $attr) {
                foreach ($attr->args as $arg) {
                    $arg->value->setAttribute(AttributeKey::SCOPE, $mutatingScope);
                }
            }
        }
    }

    private function processSwitch(Switch_ $switch, MutatingScope $mutatingScope): void
    {
        // decorate value as well
        foreach ($switch->cases as $case) {
            $case->setAttribute(AttributeKey::SCOPE, $mutatingScope);
        }
    }

    private function processCatch(Catch_ $catch, string $filePath, MutatingScope $mutatingScope): void
    {
        $varName = $catch->var instanceof Variable
        ? $this->nodeNameResolver->getName($catch->var)
        : null;

        $type = TypeCombinator::union(
            ...array_map(static fn (Name $name): ObjectType => new ObjectType((string) $name), $catch->types)
        );

        $catchMutatingScope = $mutatingScope->enterCatchType($type, $varName);
        $this->processNodes($catch->stmts, $filePath, $catchMutatingScope);
    }

    private function processTryCatch(TryCatch $tryCatch, MutatingScope $mutatingScope): void
    {
        if ($tryCatch->finally instanceof Finally_) {
            $tryCatch->finally->setAttribute(AttributeKey::SCOPE, $mutatingScope);
        }
    }

    private function processUnreachableStatementNode(
        UnreachableStatementNode $unreachableStatementNode,
        string $filePath,
        MutatingScope $mutatingScope
    ): void {
        $originalStmt = $unreachableStatementNode->getOriginalStatement();
        $originalStmt->setAttribute(AttributeKey::IS_UNREACHABLE, true);
        $originalStmt->setAttribute(AttributeKey::SCOPE, $mutatingScope);

        $this->processNodes([$originalStmt], $filePath, $mutatingScope);

        $this->hasUnreachableStatementNode = true;
    }

    private function processProperty(Property $property, MutatingScope $mutatingScope): void
    {
        foreach ($property->props as $propertyProperty) {
            $propertyProperty->setAttribute(AttributeKey::SCOPE, $mutatingScope);

            if ($propertyProperty->default instanceof Expr) {
                $propertyProperty->default->setAttribute(AttributeKey::SCOPE, $mutatingScope);
            }
        }

        foreach ($property->attrGroups as $attrGroup) {
            foreach ($attrGroup->attrs as $attribute) {
                $attribute->setAttribute(AttributeKey::SCOPE, $mutatingScope);
            }
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
        $className = $this->resolveClassName($classLike);
        $isAnonymous = $this->classAnalyzer->isAnonymousClass($classLike);

        // is anonymous class? - not possible to enter it since PHPStan 0.12.33, see https://github.com/phpstan/phpstan-src/commit/e87fb0ec26f9c8552bbeef26a868b1e5d8185e91
        if ($classLike instanceof Class_ && $isAnonymous) {
            $classReflection = $this->reflectionProvider->getAnonymousClassReflection($classLike, $mutatingScope);
        } elseif (! $this->reflectionProvider->hasClass($className)) {
            return $mutatingScope;
        } else {
            $classReflection = $this->reflectionProvider->getClass($className);
        }

        try {
            return $mutatingScope->enterClass($classReflection);
        } catch (\PHPStan\ShouldNotHappenException) {
        }

        $context = $this->privatesAccessor->getPrivateProperty($mutatingScope, 'context');
        $this->privatesAccessor->setPrivateProperty($context, 'classReflection', null);

        return $mutatingScope->enterClass($classReflection);
    }

    private function resolveClassName(Class_ | Interface_ | Trait_| Enum_ $classLike): string
    {
        if ($classLike->namespacedName instanceof Name) {
            return (string) $classLike->namespacedName;
        }

        if (! $classLike->name instanceof Identifier) {
            throw new ShouldNotHappenException();
        }

        return $classLike->name->toString();
    }

    /**
     * @param callable(Node $trait, MutatingScope $scope): void $nodeCallback
     */
    private function processTrait(Trait_ $trait, MutatingScope $mutatingScope, callable $nodeCallback): void
    {
        $traitName = $this->resolveClassName($trait);

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
        $this->decorateTraitAttrGroups($trait, $traitScope);
    }
}
