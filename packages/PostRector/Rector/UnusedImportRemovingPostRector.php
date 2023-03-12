<?php

declare(strict_types=1);

namespace Rector\PostRector\Rector;

use Nette\Utils\Strings;
use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Node\Stmt\UseUse;
use PhpParser\NodeTraverser;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\Core\Configuration\RectorConfigProvider;
use Rector\Core\PhpParser\Node\CustomNode\FileWithoutNamespace;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PhpDocParser\NodeTraverser\SimpleCallableNodeTraverser;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class UnusedImportRemovingPostRector extends AbstractPostRector
{
    public function __construct(
        private readonly SimpleCallableNodeTraverser $simpleCallableNodeTraverser,
        private readonly PhpDocInfoFactory $phpDocInfoFactory,
        private readonly RectorConfigProvider $rectorConfigProvider
    ) {
    }

    public function enterNode(Node $node): ?Node
    {
        if (! $this->rectorConfigProvider->shouldRemoveUnusedImports()) {
            return null;
        }

        if (! $node instanceof Namespace_ && ! $node instanceof FileWithoutNamespace) {
            return null;
        }

        $hasChanged = false;

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

            if ($this->isUseImportUsed($useUse, $names)) {
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
     * The higher, the later
     */
    public function getPriority(): int
    {
        // run this last
        return 100;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Removes unused import names', [
            new CodeSample(
                <<<'CODE_SAMPLE'
namespace App;

use App\SomeUnusedClass;

class SomeClass
{
}
CODE_SAMPLE
                ,
                <<<'CODE_SAMPLE'
namespace App;

class SomeClass
{
}
CODE_SAMPLE
            ),
        ]);
    }

    /**
     * @return string[]
     */
    private function findNonUseImportNames(Namespace_|FileWithoutNamespace $namespace): array
    {
        $names = [];

        $this->simpleCallableNodeTraverser->traverseNodesWithCallable($namespace, static function (Node $node) use (
            &$names
        ): int|null|Name {
            if ($node instanceof Use_) {
                return NodeTraverser::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
            }

            if (! $node instanceof Name) {
                return null;
            }

            $names[] = $node->toString();

            if ($node instanceof FullyQualified) {
                $originalName = $node->getAttribute(AttributeKey::ORIGINAL_NAME);

                if ($originalName instanceof Name) {
                    // collect original Name as well to cover namespaced used
                    $names[] = $originalName->toString();
                }
            }

            return $node;
        });

        return array_unique($names);
    }

    /**
     * @return string[]
     */
    private function findNamesInDocBlocks(Namespace_|FileWithoutNamespace $namespace): array
    {
        $names = [];

        $this->simpleCallableNodeTraverser->traverseNodesWithCallable($namespace, function (Node $node) use (
            &$names,
            &$lookupNode
        ) {
            if (! $node->hasAttribute(AttributeKey::COMMENTS)) {
                return null;
            }

            $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($node);
            $names = array_merge($names, $phpDocInfo->getAnnotationClassNames());

            if (! $lookupNode instanceof Node) {
                $lookupNode = $node;
            }
        });

        return array_unique($names);
    }

    /**
     * @return string[]
     */
    private function resolveUsedPhpAndDocNames(Namespace_|FileWithoutNamespace $namespace): array
    {
        $phpNames = $this->findNonUseImportNames($namespace);
        $docBlockNames = $this->findNamesInDocBlocks($namespace);

        return array_merge($phpNames, $docBlockNames);
    }

    /**
     * @param string[]  $names
     */
    private function isUseImportUsed(UseUse $useUse, array $names): bool
    {
        $comparedName = $useUse->name->toString();
        if (in_array($comparedName, $names, true)) {
            return true;
        }

        $namespacedPrefix = Strings::after($comparedName, '\\', -1) . '\\';

        if ($namespacedPrefix === '\\') {
            $namespacedPrefix = $comparedName . '\\';
        }

        $alias = $useUse->alias instanceof Identifier
            ? $useUse->alias->toString()
            : null;

        // match partial import
        foreach ($names as $name) {
            if (str_ends_with($comparedName, $name)) {
                return true;
            }

            if (str_starts_with($name, $namespacedPrefix)) {
                return true;
            }

            if (! is_string($alias)) {
                continue;
            }

            if ($alias === $name) {
                return true;
            }

            if (str_contains($name, '\\')) {
                $namePrefix = Strings::before($name, '\\', 1);
                if ($alias === $namePrefix) {
                    return true;
                }
            }
        }

        return false;
    }
}
