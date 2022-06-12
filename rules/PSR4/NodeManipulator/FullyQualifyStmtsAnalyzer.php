<?php

declare(strict_types=1);

namespace Rector\PSR4\NodeManipulator;

use PhpParser\Node;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt;
use PHPStan\Reflection\Constant\RuntimeConstantReflection;
use PHPStan\Reflection\ReflectionProvider;
use Rector\Core\Configuration\Option;
use Rector\Core\Enum\ObjectReference;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\Astral\NodeTraverser\SimpleCallableNodeTraverser;
use Symplify\PackageBuilder\Parameter\ParameterProvider;

final class FullyQualifyStmtsAnalyzer
{
    public function __construct(
        private readonly ParameterProvider $parameterProvider,
        private readonly SimpleCallableNodeTraverser $simpleCallableNodeTraverser,
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly ReflectionProvider $reflectionProvider
    ) {
    }

    /**
     * @param Stmt[] $stmts
     */
    public function process(array $stmts, string $expectedNamespace, bool $migrateInnerClassReference): void
    {
        // no need to
        if ($this->parameterProvider->provideBoolParameter(Option::AUTO_IMPORT_NAMES)) {
            return;
        }

        // FQNize all class names
        $this->simpleCallableNodeTraverser->traverseNodesWithCallable($stmts, function (Node $node) use (
            $expectedNamespace,
            $migrateInnerClassReference
        ): ?FullyQualified {
            if (! $node instanceof Name) {
                return null;
            }

            $name = $this->nodeNameResolver->getName($node);
            if (in_array($name, [ObjectReference::STATIC, ObjectReference::PARENT, ObjectReference::SELF], true)) {
                return null;
            }

            if ($this->isNativeConstant($node)) {
                return null;
            }

            if (! $migrateInnerClassReference) {
                return new FullyQualified($name);
            }

            if (str_starts_with($name, '\\')) {
                return new FullyQualified($name);
            }

            if ($this->isNativeClass($name)) {
                return new FullyQualified($name);
            }

            return new FullyQualified($expectedNamespace . '\\' . $name);
        });
    }

    private function isNativeClass(string $name): bool
    {
        if (! $this->reflectionProvider->hasClass($name)) {
            return false;
        }

        $classReflection = $this->reflectionProvider->getClass($name);
        return $classReflection->isBuiltin();
    }

    private function isNativeConstant(Name $name): bool
    {
        $parent = $name->getAttribute(AttributeKey::PARENT_NODE);
        if (! $parent instanceof ConstFetch) {
            return false;
        }

        $scope = $name->getAttribute(AttributeKey::SCOPE);
        if (! $this->reflectionProvider->hasConstant($name, $scope)) {
            return false;
        }

        $constantReflection = $this->reflectionProvider->getConstant($name, $scope);
        return $constantReflection instanceof RuntimeConstantReflection;
    }
}
