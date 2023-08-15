<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\PHPStan\Scope;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Enum_;
use PhpParser\Node\Stmt\Finally_;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\Switch_;
use PhpParser\Node\Stmt\Trait_;
use PhpParser\Node\Stmt\TryCatch;
use PhpParser\NodeTraverser;
use PHPStan\Analyser\MutatingScope;
use PHPStan\Analyser\NodeScopeResolver;
use PHPStan\Analyser\ScopeContext;
use PHPStan\Node\UnreachableStatementNode;
use PHPStan\Reflection\ReflectionProvider;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\NodeAnalyzer\ClassAnalyzer;
use Rector\Core\PhpParser\Node\CustomNode\FileWithoutNamespace;
use Rector\Core\PHPStan\NodeVisitor\ScopeFromCurrentStmtNodeVisitor;
use Rector\Core\PHPStan\NodeVisitor\WrappedNodeRestoringNodeVisitor;
use Rector\Core\Util\Reflection\PrivatesAccessor;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\PHPStan\Scope\Contract\NodeVisitor\ScopeResolverNodeVisitorInterface;
use Webmozart\Assert\Assert;
use PHPStan\Type\ObjectType;
use PHPStan\Type\TypeCombinator;
use Rector\Core\NodeAnalyzer\ScopeAnalyzer;

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
        private readonly ClassAnalyzer $classAnalyzer,
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly ScopeAnalyzer $scopeAnalyzer
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
                $this->nodeScopeResolver->processNodes($node->stmts, $mutatingScope, $nodeCallback);

                return;
            }

            if ($node instanceof Switch_) {
                $this->processSwitch($node, $mutatingScope);
            }

            if ($node instanceof TryCatch) {
                $this->processTryCatch($node, $filePath, $mutatingScope);
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
                $node->setAttribute(AttributeKey::SCOPE, $mutatingScope);

                return;
            }

            // special case for unreachable nodes
            if ($node instanceof UnreachableStatementNode) {
                $this->processUnreachableStatementNode($node, $filePath, $mutatingScope);
                return;
            }

            $node->setAttribute(AttributeKey::SCOPE, $mutatingScope);
        };

        return $this->processNodesWithDependentFiles($stmts, $scope, $nodeCallback);
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

    public function hasUnreachableStatementNode(): bool
    {
        return $this->hasUnreachableStatementNode;
    }

    public function resetHasUnreachableStatementNode(): void
    {
        $this->hasUnreachableStatementNode = false;
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

    /**
     * @param Stmt[] $stmts
     * @param callable(Node $node, MutatingScope $scope): void $nodeCallback
     * @return Stmt[]
     */
    private function processNodesWithDependentFiles(
        array $stmts,
        MutatingScope $mutatingScope,
        callable $nodeCallback
    ): array {
        $this->nodeScopeResolver->processNodes($stmts, $mutatingScope, $nodeCallback);

        $nodeTraverser = new NodeTraverser();
        $nodeTraverser->addVisitor(new WrappedNodeRestoringNodeVisitor());
        $nodeTraverser->addVisitor(new ScopeFromCurrentStmtNodeVisitor($this->scopeAnalyzer));
        $nodeTraverser->traverse($stmts);

        return $stmts;
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
        $this->nodeScopeResolver->processNodes($trait->stmts, $traitScope, $nodeCallback);
    }
}
