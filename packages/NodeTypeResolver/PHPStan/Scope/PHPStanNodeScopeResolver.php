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
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\Declare_;
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
use PHPStan\AnalysedCodeException;
use PHPStan\Analyser\MutatingScope;
use PHPStan\Analyser\NodeScopeResolver;
use PHPStan\Analyser\ScopeContext;
use PHPStan\Node\UnreachableStatementNode;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\ObjectType;
use PHPStan\Type\TypeCombinator;
use Rector\Caching\Detector\ChangedFilesDetector;
use Rector\Caching\FileSystem\DependencyResolver;
use Rector\Core\Contract\PhpParser\Node\StmtsAwareInterface;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\NodeAnalyzer\ClassAnalyzer;
use Rector\Core\PhpParser\Node\CustomNode\FileWithoutNamespace;
use Rector\Core\PhpParser\NodeTraverser\FileWithoutNamespaceNodeTraverser;
use Rector\Core\PHPStan\NodeVisitor\WrappedNodeRestoringNodeVisitor;
use Rector\Core\Util\Reflection\PrivatesAccessor;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\PHPStan\Scope\Contract\NodeVisitor\ScopeResolverNodeVisitorInterface;
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

    /**
     * @param ScopeResolverNodeVisitorInterface[] $nodeVisitors
     */
    public function __construct(
        private readonly ChangedFilesDetector $changedFilesDetector,
        private readonly DependencyResolver $dependencyResolver,
        private readonly NodeScopeResolver $nodeScopeResolver,
        private readonly ReflectionProvider $reflectionProvider,
        iterable $nodeVisitors,
        private readonly ScopeFactory $scopeFactory,
        private readonly PrivatesAccessor $privatesAccessor,
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly ClassAnalyzer $classAnalyzer,
        private readonly FileWithoutNamespaceNodeTraverser $fileWithoutNamespaceNodeTraverser
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
        $isScopeRefreshing = $formerMutatingScope instanceof MutatingScope;

        /**
         * The stmts must be array of Stmt, or it will be silently skipped by PHPStan
         * @see vendor/phpstan/phpstan/phpstan.phar/src/Analyser/NodeScopeResolver.php:282
         */

        Assert::allIsInstanceOf($stmts, Stmt::class);

        $isInitFileWithoutNamespace = false;
        if (! $isScopeRefreshing && ! current($stmts) instanceof FileWithoutNamespace) {
            $stmts = $this->fileWithoutNamespaceNodeTraverser->traverse($stmts);

            $currentStmt = current($stmts);
            if ($currentStmt instanceof FileWithoutNamespace) {
                $this->nodeTraverser->traverse($stmts);
                $stmts = $currentStmt->stmts;

                $isInitFileWithoutNamespace = true;
            }
        }

        if (! $isInitFileWithoutNamespace) {
            $this->nodeTraverser->traverse($stmts);
        }

        $scope = $formerMutatingScope ?? $this->scopeFactory->createFromFile($filePath);

        // skip chain method calls, performance issue: https://github.com/phpstan/phpstan/issues/254
        $nodeCallback = function (Node $node, MutatingScope $mutatingScope) use (
            &$nodeCallback,
            $isScopeRefreshing,
            $filePath
        ): void {
            if ((
                $node instanceof Expression ||
                $node instanceof Return_ ||
                $node instanceof Assign ||
                $node instanceof EnumCase ||
                $node instanceof AssignOp ||
                $node instanceof Cast
            ) && $node->expr instanceof Expr) {
                $node->expr->setAttribute(AttributeKey::SCOPE, $mutatingScope);
            }

            if ($node instanceof Assign || $node instanceof AssignOp) {
                $this->processAssign($node, $mutatingScope);
            }

            if ($node instanceof Ternary) {
                $this->processTernary($node, $mutatingScope);
            }

            if ($node instanceof BinaryOp) {
                $this->processBinaryOp($node, $mutatingScope);
            }

            if ($node instanceof Arg) {
                $node->value->setAttribute(AttributeKey::SCOPE, $mutatingScope);
            }

            if ($node instanceof Foreach_) {
                // decorate value as well
                $node->valueVar->setAttribute(AttributeKey::SCOPE, $mutatingScope);
                if ($node->valueVar instanceof Array_) {
                    $this->processArray($node->valueVar, $mutatingScope);
                }
            }

            if ($node instanceof Array_) {
                $this->processArray($node, $mutatingScope);
            }

            if ($node instanceof Property) {
                $this->processProperty($node, $mutatingScope);
            }

            if ($node instanceof Switch_) {
                $this->processSwitch($node, $mutatingScope);
            }

            if ($node instanceof TryCatch) {
                $this->processTryCatch($node, $filePath, $mutatingScope);
            }

            if ($node instanceof ArrayItem) {
                $this->processArrayItem($node, $mutatingScope);
            }

            if ($node instanceof FuncCall && $node->name instanceof Expr) {
                $node->name->setAttribute(AttributeKey::SCOPE, $mutatingScope);
            }

            if ($node instanceof NullableType) {
                $node->type->setAttribute(AttributeKey::SCOPE, $mutatingScope);
            }

            if ($node instanceof UnionType || $node instanceof IntersectionType) {
                foreach ($node->types as $type) {
                    $type->setAttribute(AttributeKey::SCOPE, $mutatingScope);
                }
            }

            if ($node instanceof StaticPropertyFetch) {
                $node->class->setAttribute(AttributeKey::SCOPE, $mutatingScope);
                $node->name->setAttribute(AttributeKey::SCOPE, $mutatingScope);
            }

            if ($node instanceof PropertyFetch) {
                $node->var->setAttribute(AttributeKey::SCOPE, $mutatingScope);
                $node->name->setAttribute(AttributeKey::SCOPE, $mutatingScope);
            }

            if ($node instanceof CallLike) {
                $this->processCallike($node, $mutatingScope);
            }

            if ($node instanceof ConstFetch) {
                $node->name->setAttribute(AttributeKey::SCOPE, $mutatingScope);
            }

            if ($node instanceof ClassConstFetch) {
                $node->class->setAttribute(AttributeKey::SCOPE, $mutatingScope);
                $node->name->setAttribute(AttributeKey::SCOPE, $mutatingScope);
            }

            if ($node instanceof Trait_) {
                $traitName = $this->resolveClassName($node);

                $traitClassReflection = $this->reflectionProvider->getClass($traitName);

                $traitScope = clone $mutatingScope;

                $scopeContext = $this->privatesAccessor->getPrivatePropertyOfClass(
                    $traitScope,
                    self::CONTEXT,
                    ScopeContext::class
                );

                $traitContext = clone $scopeContext;

                // before entering the class/trait again, we have to tell scope no class was set, otherwise it crashes
                $this->privatesAccessor->setPrivatePropertyOfClass(
                    $traitContext,
                    'classReflection',
                    $traitClassReflection,
                    ClassReflection::class
                );
                $this->privatesAccessor->setPrivatePropertyOfClass(
                    $traitScope,
                    self::CONTEXT,
                    $traitContext,
                    ScopeContext::class
                );

                $node->setAttribute(AttributeKey::SCOPE, $traitScope);
                $this->nodeScopeResolver->processNodes($node->stmts, $traitScope, $nodeCallback);
                $this->decorateTraitAttrGroups($node, $traitScope);

                return;
            }

            // the class reflection is resolved AFTER entering to class node
            // so we need to get it from the first after this one
            if ($node instanceof Class_ || $node instanceof Interface_ || $node instanceof Enum_) {
                /** @var MutatingScope $mutatingScope */
                $mutatingScope = $this->resolveClassOrInterfaceScope($node, $mutatingScope, $isScopeRefreshing);
            }

            if ($node instanceof Stmt) {
                $this->setChildOfUnreachableStatementNodeAttribute($node, $mutatingScope);
            }

            // special case for unreachable nodes
            if ($node instanceof UnreachableStatementNode) {
                $this->processUnreachableStatementNode($node, $filePath, $mutatingScope);
            } else {
                $node->setAttribute(AttributeKey::SCOPE, $mutatingScope);
            }
        };

        return $this->processNodesWithDependentFiles($filePath, $stmts, $scope, $nodeCallback);
    }

    private function processCallike(CallLike $callLike, MutatingScope $mutatingScope): void
    {
        $this->processArgsForCallike($callLike, $mutatingScope);

        if ($callLike instanceof StaticCall) {
            $callLike->class->setAttribute(AttributeKey::SCOPE, $mutatingScope);
            $callLike->name->setAttribute(AttributeKey::SCOPE, $mutatingScope);
        }

        if ($callLike instanceof MethodCall) {
            $callLike->var->setAttribute(AttributeKey::SCOPE, $mutatingScope);
            $callLike->name->setAttribute(AttributeKey::SCOPE, $mutatingScope);
        }

        if ($callLike instanceof FuncCall) {
            $callLike->name->setAttribute(AttributeKey::SCOPE, $mutatingScope);
        }

        if ($callLike instanceof New_ && ! $callLike->class instanceof Class_) {
            $callLike->class->setAttribute(AttributeKey::SCOPE, $mutatingScope);
        }

        if ($callLike instanceof NullsafeMethodCall) {
            $callLike->var->setAttribute(AttributeKey::SCOPE, $mutatingScope);
            $callLike->name->setAttribute(AttributeKey::SCOPE, $mutatingScope);
        }
    }

    private function processArgsForCallike(Expr $expr, MutatingScope $mutatingScope): void
    {
        if (! $expr instanceof CallLike) {
            return;
        }

        if (! $expr->isFirstClassCallable()) {
            foreach ($expr->getArgs() as $arg) {
                $arg->value->setAttribute(AttributeKey::SCOPE, $mutatingScope);
            }
        }
    }

    private function processAssign(Assign|AssignOp $assign, MutatingScope $mutatingScope): void
    {
        if (! $assign->var instanceof Variable || ! $assign->var->name instanceof Variable) {
            $assign->var->setAttribute(AttributeKey::SCOPE, $mutatingScope);
            return;
        }

        $expr = $assign;

        while ($expr instanceof Assign || $expr instanceof AssignOp) {
            $this->processArgsForCallike($expr->expr, $mutatingScope);

            // decorate value as well
            $expr->var->setAttribute(AttributeKey::SCOPE, $mutatingScope);
            $expr = $expr->expr;
        }
    }

    private function setChildOfUnreachableStatementNodeAttribute(Stmt $stmt, MutatingScope $mutatingScope): void
    {
        if (! $stmt instanceof StmtsAwareInterface && ! $stmt instanceof ClassLike && ! $stmt instanceof Declare_) {
            return;
        }

        if ($stmt->getAttribute(AttributeKey::IS_UNREACHABLE) !== true) {
            return;
        }

        if ($stmt->stmts === null) {
            return;
        }

        foreach ($stmt->stmts as $childStmt) {
            $childStmt->setAttribute(AttributeKey::IS_UNREACHABLE, true);
            $childStmt->setAttribute(AttributeKey::SCOPE, $mutatingScope);
        }
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

    private function processTryCatch(TryCatch $tryCatch, string $filePath, MutatingScope $mutatingScope): void
    {
        foreach ($tryCatch->catches as $catch) {
            $varName = $catch->var instanceof Variable
                ? $this->nodeNameResolver->getName($catch->var)
                : null;

            $type = TypeCombinator::union(
                ...array_map(static fn (Name $name): ObjectType => new ObjectType((string) $name), $catch->types)
            );

            $catchMutatingScope = $mutatingScope->enterCatchType($type, $varName);
            $this->processNodes($catch->stmts, $filePath, $catchMutatingScope);
        }

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

        $parentNode = $unreachableStatementNode->getAttribute(AttributeKey::PARENT_NODE);
        if (! $parentNode instanceof StmtsAwareInterface && ! $parentNode instanceof ClassLike && ! $parentNode instanceof Declare_) {
            return;
        }

        $stmtKey = $unreachableStatementNode->getAttribute(AttributeKey::STMT_KEY);
        $totalKeys = $parentNode->stmts === null ? 0 : count($parentNode->stmts);

        for ($key = $stmtKey + 1; $key < $totalKeys; ++$key) {
            if (! isset($parentNode->stmts[$key])) {
                continue;
            }

            $parentNode->stmts[$key]->setAttribute(AttributeKey::IS_UNREACHABLE, true);
            $this->processNodes([$parentNode->stmts[$key]], $filePath, $mutatingScope);
        }
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

    /**
     * @param Stmt[] $stmts
     * @param callable(Node $node, MutatingScope $scope): void $nodeCallback
     * @return Stmt[]
     */
    private function processNodesWithDependentFiles(
        string $filePath,
        array $stmts,
        MutatingScope $mutatingScope,
        callable $nodeCallback
    ): array {
        $this->nodeScopeResolver->processNodes($stmts, $mutatingScope, $nodeCallback);
        $this->resolveAndSaveDependentFiles($stmts, $mutatingScope, $filePath);

        $nodeTraverser = new NodeTraverser();
        $nodeTraverser->addVisitor(new WrappedNodeRestoringNodeVisitor());
        $nodeTraverser->traverse($stmts);

        return $stmts;
    }

    private function resolveClassOrInterfaceScope(
        Class_ | Interface_ | Enum_ $classLike,
        MutatingScope $mutatingScope,
        bool $isScopeRefreshing
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

        // on refresh, remove entered class avoid entering the class again
        if ($isScopeRefreshing && $mutatingScope->isInClass() && ! $isAnonymous) {
            $context = $this->privatesAccessor->getPrivateProperty($mutatingScope, 'context');
            $this->privatesAccessor->setPrivateProperty($context, 'classReflection', null);
        }

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
     * @param Stmt[] $stmts
     */
    private function resolveAndSaveDependentFiles(
        array $stmts,
        MutatingScope $mutatingScope,
        string $filePath
    ): void {
        $dependentFiles = [];
        foreach ($stmts as $stmt) {
            try {
                $nodeDependentFiles = $this->dependencyResolver->resolveDependencies($stmt, $mutatingScope);
                $dependentFiles = array_merge($dependentFiles, $nodeDependentFiles);
            } catch (AnalysedCodeException) {
                // @ignoreException
            }
        }

        $this->changedFilesDetector->addFileDependentFiles($filePath, $dependentFiles);
    }
}
