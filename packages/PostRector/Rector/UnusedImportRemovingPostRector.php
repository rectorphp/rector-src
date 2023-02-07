<?php

declare(strict_types=1);

namespace Rector\PostRector\Rector;

use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Use_;
use PhpParser\NodeFinder;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class UnusedImportRemovingPostRector extends AbstractPostRector
{
    public function __construct(
        private readonly NodeFinder $nodeFinder,
    ) {
    }

    public function enterNode(Node $node): ?Node
    {
        if (! $node instanceof Namespace_) {
            return null;
        }

        /** @var Name[] $names */
        $names = $this->nodeFinder->findInstanceOf($node, Name::class);

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
        }

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
}
