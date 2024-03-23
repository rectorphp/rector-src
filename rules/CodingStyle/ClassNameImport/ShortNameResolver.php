<?php

declare(strict_types=1);

namespace Rector\CodingStyle\ClassNameImport;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\Namespace_;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PhpDocParser\NodeTraverser\SimpleCallableNodeTraverser;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\PhpParser\Node\CustomNode\FileWithoutNamespace;
use Rector\ValueObject\Application\File;

/**
 * @see \Rector\Tests\CodingStyle\ClassNameImport\ShortNameResolver\ShortNameResolverTest
 */
final class ShortNameResolver
{
    /**
     * @var array<string, string[]>
     */
    private array $shortNamesByFilePath = [];

    public function __construct(
        private readonly SimpleCallableNodeTraverser $simpleCallableNodeTraverser,
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly BetterNodeFinder $betterNodeFinder
    ) {
    }

    /**
     * @return array<string, string>
     */
    public function resolveFromFile(File $file): array
    {
        $filePath = $file->getFilePath();

        if (isset($this->shortNamesByFilePath[$filePath])) {
            return $this->shortNamesByFilePath[$filePath];
        }

        $shortNamesToFullyQualifiedNames = $this->resolveForStmts($file->getNewStmts());
        $this->shortNamesByFilePath[$filePath] = $shortNamesToFullyQualifiedNames;

        return $shortNamesToFullyQualifiedNames;
    }

    /**
     * Collects all "class <SomeClass>", "trait <SomeTrait>" and "interface <SomeInterface>"
     * @return string[]
     */
    public function resolveShortClassLikeNames(File $file): array
    {
        $newStmts = $file->getNewStmts();

        /** @var Namespace_[]|FileWithoutNamespace[] $namespaces */
        $namespaces = array_filter(
            $newStmts,
            static fn (Stmt $stmt): bool => $stmt instanceof Namespace_ || $stmt instanceof FileWithoutNamespace
        );
        if (count($namespaces) !== 1) {
            // only handle single namespace nodes
            return [];
        }

        $namespace = current($namespaces);

        /** @var ClassLike[] $classLikes */
        $classLikes = $this->betterNodeFinder->findInstanceOf($namespace->stmts, ClassLike::class);

        $shortClassLikeNames = [];
        foreach ($classLikes as $classLike) {
            $shortClassLikeNames[] = $this->nodeNameResolver->getShortName($classLike);
        }

        return array_unique($shortClassLikeNames);
    }

    /**
     * @param Stmt[] $stmts
     * @return array<string, string>
     */
    private function resolveForStmts(array $stmts): array
    {
        $shortNamesToFullyQualifiedNames = [];

        $this->simpleCallableNodeTraverser->traverseNodesWithCallable($stmts, function (Node $node) use (
            &$shortNamesToFullyQualifiedNames
        ): null {
            // class name is used!
            if ($node instanceof ClassLike && $node->name instanceof Identifier) {
                $fullyQualifiedName = $this->nodeNameResolver->getName($node);
                if ($fullyQualifiedName === null) {
                    return null;
                }

                $shortNamesToFullyQualifiedNames[$node->name->toString()] = $fullyQualifiedName;
                return null;
            }

            if (! $node instanceof Name) {
                return null;
            }

            $originalName = $node->getAttribute(AttributeKey::ORIGINAL_NAME);
            if (! $originalName instanceof Name) {
                return null;
            }

            // already short
            if (\str_contains($originalName->toString(), '\\')) {
                return null;
            }

            $shortNamesToFullyQualifiedNames[$originalName->toString()] = $this->nodeNameResolver->getName($node);

            return null;
        });

        return $shortNamesToFullyQualifiedNames;
    }
}
