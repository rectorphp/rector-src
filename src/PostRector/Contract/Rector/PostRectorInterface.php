<?php

declare(strict_types=1);

namespace Rector\PostRector\Contract\Rector;

use PhpParser\Node\Stmt;
use PhpParser\NodeVisitor;
use Rector\ValueObject\Application\File;

/**
 * @internal
 */
interface PostRectorInterface extends NodeVisitor
{
    /**
     * @param Stmt[] $stmts
     */
    public function shouldTraverse(array $stmts): bool;

    public function setFile(File $file): void;

    /**
     * Used to sort PostRectors in the order in which they should run
     * A smaller number means higher priority (should run first)
     */
    public function getPriority(): int;
}
