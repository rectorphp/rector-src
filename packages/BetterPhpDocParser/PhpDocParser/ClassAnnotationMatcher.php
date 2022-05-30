<?php

declare(strict_types=1);

namespace Rector\BetterPhpDocParser\PhpDocParser;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\GroupUse;
use PhpParser\Node\Stmt\Use_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ReflectionProvider;
use Rector\CodingStyle\NodeAnalyzer\UseImportNameMatcher;
use Rector\Naming\Naming\UseImportsResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;

/**
 * Matches "@ORM\Entity" to FQN names based on use imports in the file
 */
final class ClassAnnotationMatcher
{
    /**
     * @var array<string, string>
     */
    private array $fullyQualifiedNameByHash = [];

    public function __construct(
        private readonly UseImportNameMatcher $useImportNameMatcher,
        private readonly UseImportsResolver $useImportsResolver,
        private readonly ReflectionProvider $reflectionProvider
    ) {
    }

    public function resolveTagFullyQualifiedName(
        string $tag,
        Node $node,
        bool $returnNullOnUnknownClass = false
    ): ?string {
        $uniqueHash = $tag . spl_object_hash($node);
        if (isset($this->fullyQualifiedNameByHash[$uniqueHash])) {
            return $this->fullyQualifiedNameByHash[$uniqueHash];
        }

        $tag = ltrim($tag, '@');

        $uses = $this->useImportsResolver->resolveForNode($node);
        $fullyQualifiedClass = $this->resolveFullyQualifiedClass($uses, $node, $tag);

        if ($fullyQualifiedClass === null) {
            if ($returnNullOnUnknownClass) {
                return null;
            }

            $fullyQualifiedClass = $tag;
        }

        $this->fullyQualifiedNameByHash[$uniqueHash] = $fullyQualifiedClass;

        return $fullyQualifiedClass;
    }

    /**
     * @param Use_[]|GroupUse[] $uses
     */
    private function resolveFullyQualifiedClass(array $uses, Node $node, string $tag): ?string
    {
        $scope = $node->getAttribute(AttributeKey::SCOPE);

        if ($scope instanceof Scope) {
            $namespace = $scope->getNamespace();
            if ($namespace !== null) {
                $namespacedTag = $namespace . '\\' . $tag;
                if ($this->reflectionProvider->hasClass($namespacedTag)) {
                    return $namespacedTag;
                }

                if (! str_contains($tag, '\\')) {
                    return $this->resolveAsAliased($uses, $tag);
                }

                if (str_starts_with($tag, '\\')
                    && substr_count($tag, '\\') === 1
                    && $this->reflectionProvider->hasClass($tag)
                ) {
                    // Global Class
                    return $tag;
                }
            }
        }

        $class = $this->useImportNameMatcher->matchNameWithUses($tag, $uses);
        return $this->resolveClass($class);
    }

    /**
     * @param Use_[]|GroupUse[] $uses
     */
    private function resolveAsAliased(array $uses, string $tag): ?string
    {
        foreach ($uses as $use) {
            $prefix = $use instanceof GroupUse
                ? $use->prefix . '\\'
                : '';

            foreach ($use->uses as $useUse) {
                if (! $useUse->alias instanceof Identifier) {
                    continue;
                }

                if ($useUse->alias->toString() === $tag) {
                    $class = $prefix . $useUse->name->toString();
                    return $this->resolveClass($class);
                }
            }
        }

        $class = $this->useImportNameMatcher->matchNameWithUses($tag, $uses);
        return $this->resolveClass($class);
    }

    private function resolveClass(?string $class): ?string
    {
        if (null === $class) {
            return null;
        }
        return $this->reflectionProvider->hasClass($class) ? $class : null;
    }
}
