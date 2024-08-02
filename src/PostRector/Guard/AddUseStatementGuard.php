<?php

declare(strict_types=1);

namespace Rector\PostRector\Guard;

use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\InlineHTML;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\NodeFinder;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\ValueObject\Application\File;

class AddUseStatementGuard
{
    /**
     * @param Stmt[] $stmts
     */
    public function shouldTraverse(File $file, array $stmts): bool
    {
        $shouldAddUseStatement = 'should_add_use_statement_' . $file->getFilePath();

        // already set in current file, no need to repeat the logic
        if (SimpleParameterProvider::hasParameter($shouldAddUseStatement)) {
            return SimpleParameterProvider::provideBoolParameter($shouldAddUseStatement);
        }

        $totalNamespaces = 0;
        $shouldTraverse = true;

        // just loop the first level stmts to locate namespace to improve performance
        // as namespace is always on first level
        foreach ($stmts as $stmt) {
            if ($stmt instanceof Namespace_) {
                ++$totalNamespaces;
            }

            // skip if 2 namespaces are present
            if ($totalNamespaces === 2) {
                $shouldTraverse = false;
                break;
            }
        }

        if ($shouldTraverse) {
            $nodeFinder = new NodeFinder();
            $shouldTraverse = ! (bool) $nodeFinder->findFirstInstanceOf($stmts, InlineHTML::class);
        }

        SimpleParameterProvider::setParameter($shouldAddUseStatement, $shouldTraverse);
        return $shouldTraverse;
    }
}
