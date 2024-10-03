<?php

declare(strict_types=1);

namespace Rector\PostRector\Rector;

use Nette\Utils\Strings;
use PhpParser\Comment;
use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Node\Stmt\UseUse;
use PhpParser\NodeTraverser;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PhpDocParser\NodeTraverser\SimpleCallableNodeTraverser;
use Rector\PhpParser\Node\CustomNode\FileWithoutNamespace;

final class UnusedImportRemovingPostRector extends AbstractPostRector
{
    public function __construct(
        private readonly SimpleCallableNodeTraverser $simpleCallableNodeTraverser,
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
    ) {
    }

    public function enterNode(Node $node): ?Node
    {
        if (! $node instanceof Namespace_ && ! $node instanceof FileWithoutNamespace) {
            return null;
        }

        $hasChanged = false;

        $namespaceName = $node instanceof Namespace_ && $node->name instanceof Name
            ? $node->name
            : null;

        $names = $this->resolveUsedPhpAndDocNames($node);

        foreach ($node->stmts as $key => $namespaceStmt) {
            if (! $namespaceStmt instanceof Use_) {
                continue;
            }

            if ($namespaceStmt->uses === []) {
                unset($node->stmts[$key]);
                $hasChanged = true;

                continue;
            }

            $useUse = $namespaceStmt->uses[0];
            if ($this->isUseImportUsed($useUse, $names, $namespaceName)) {
                continue;
            }

            unset($node->stmts[$key]);
            $hasChanged = true;
        }

        if ($hasChanged === false) {
            return null;
        }

        $node->stmts = array_values($node->stmts);
        return $node;
    }

    /**
     * @return list<string>
     */
    private function findNonUseImportNames(Namespace_|FileWithoutNamespace $namespace): array
    {
        $names = [];

        $this->simpleCallableNodeTraverser->traverseNodesWithCallable($namespace->stmts, static function (Node $node) use (
            &$names
        ): int|null|Name {
            if ($node instanceof Use_) {
                return NodeTraverser::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
            }

            if (! $node instanceof Name) {
                return null;
            }

            if ($node instanceof FullyQualified) {
                $originalName = $node->getAttribute(AttributeKey::ORIGINAL_NAME);

                if ($originalName instanceof Name) {
                    // collect original Name as cover namespaced used
                    $names[] = $originalName->toString();
                    return $node;
                }
            }

            $names[] = $node->toString();
            return $node;
        });

        return $names;
    }

    /**
     * @return list<string>
     */
    private function findNamesInDocBlocks(Namespace_|FileWithoutNamespace $namespace): array
    {
        $names = [];

        $this->simpleCallableNodeTraverser->traverseNodesWithCallable($namespace, function (Node $node) use (
            &$names
        ) {
            $comments = $node->getComments();
            if ($comments === []) {
                return null;
            }

            $docs = array_filter($comments, static fn (Comment $comment): bool => $comment instanceof Doc);
            if ($docs === []) {
                return null;
            }

            $totalDocs = count($docs);
            foreach ($docs as $doc) {
                $nodeToCheck = $totalDocs === 1 ? $node : clone $node;
                if ($totalDocs > 1) {
                    $nodeToCheck->setDocComment($doc);
                }

                $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($nodeToCheck);
                $names = [...$names, ...$phpDocInfo->getAnnotationClassNames()];

                $constFetchNodeNames = $phpDocInfo->getConstFetchNodeClassNames();
                $names = [...$names, ...$constFetchNodeNames];

                $genericTagClassNames = $phpDocInfo->getGenericTagClassNames();
                $names = [...$names, ...$genericTagClassNames];

                $arrayItemTagClassNames = $phpDocInfo->getArrayItemNodeClassNames();
                $names = [...$names, ...$arrayItemTagClassNames];
            }
        });

        return $names;
    }

    /**
     * @return list<string>
     */
    private function resolveUsedPhpAndDocNames(Namespace_|FileWithoutNamespace $namespace): array
    {
        $phpNames = $this->findNonUseImportNames($namespace);
        $docBlockNames = $this->findNamesInDocBlocks($namespace);

        $names = [...$phpNames, ...$docBlockNames];
        return array_unique(array_map(strtolower(...), $names));
    }

    /**
     * @param list<string> $names
     */
    private function isUseImportUsed(UseUse $useUse, array $names, ?Name $namespaceName): bool
    {
        $comparedName = strtolower($useUse->alias instanceof Identifier
            ? $useUse->alias->toString()
            : $useUse->name->toString());

        if (in_array($comparedName, $names, true)) {
            return true;
        }

        $namespacedPrefix = Strings::after($comparedName, '\\', -1) . '\\';

        if ($namespacedPrefix === '\\') {
            $namespacedPrefix = $comparedName . '\\';
        }

        $lastName = strtolower($useUse->name->getLast());
        $namespaceName = $namespaceName instanceof Name ? strtolower($namespaceName->toString()) : null;

        // match partial import
        foreach ($names as $name) {
            if ($this->isSubNamespace($name, $comparedName, $namespacedPrefix)) {
                return true;
            }

            if (! str_starts_with($name, $lastName . '\\')) {
                continue;
            }

            if ($namespaceName === null) {
                return true;
            }

            if (! str_starts_with($name, $namespaceName . '\\')) {
                return true;
            }
        }

        return false;
    }

    private function isSubNamespace(string $name, string $comparedName, string $namespacedPrefix): bool
    {
        if (str_ends_with($comparedName, '\\' . $name)) {
            return true;
        }

        if (str_starts_with($name, $namespacedPrefix)) {
            $subNamespace = substr($name, strlen($namespacedPrefix));
            return ! str_contains($subNamespace, '\\');
        }

        return false;
    }
}
