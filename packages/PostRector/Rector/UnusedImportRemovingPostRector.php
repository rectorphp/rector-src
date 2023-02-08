<?php

declare(strict_types=1);

namespace Rector\PostRector\Rector;

use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Use_;
use PhpParser\NodeTraverser;
use Rector\PhpDocParser\NodeTraverser\SimpleCallableNodeTraverser;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class UnusedImportRemovingPostRector extends AbstractPostRector
{
    public function __construct(
        private readonly SimpleCallableNodeTraverser $simpleCallableNodeTraverser
    ) {
    }

    public function enterNode(Node $node): ?Node
    {
        if (! $node instanceof Namespace_) {
            return null;
        }

        $hasChanged = false;
        $names = $this->findNonUseImportNames($node);

        foreach ($node->stmts as $key => $namespaceStmt) {
            if (! $namespaceStmt instanceof Use_) {
                continue;
            }

            $useName = $namespaceStmt->uses[0]->name->toString();
            foreach ($names as $name) {
                // use import is used → skip it
                if ($name->toString() === $useName) {
                    continue 2;
                }
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

    public function getPriority(): int
    {
        // run this last
        return 2000;
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
     * @return Name[]
     */
    private function findNonUseImportNames(Namespace_ $namespace): array
    {
        /** @var Name[] $names */
        $names = [];

        $this->simpleCallableNodeTraverser->traverseNodesWithCallable($namespace, static function (Node $node) use (&$names) {
            if ($node instanceof Use_) {
                return NodeTraverser::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
            }

            if (! $node instanceof Name) {
                return null;
            }

            $names[] = $node;
            return $node;
        });

        return $names;
    }
}
