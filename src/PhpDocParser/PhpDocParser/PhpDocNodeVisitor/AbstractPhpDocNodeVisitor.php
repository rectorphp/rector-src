<?php

declare(strict_types=1);

namespace Rector\PhpDocParser\PhpDocParser\PhpDocNodeVisitor;

use PHPStan\PhpDocParser\Ast\Node;
use Rector\PhpDocParser\PhpDocParser\Contract\PhpDocNodeVisitorInterface;

/**
 * Inspired by https://github.com/nikic/PHP-Parser/blob/master/lib/PhpParser/NodeVisitorAbstract.php
 */
abstract class AbstractPhpDocNodeVisitor implements PhpDocNodeVisitorInterface
{
    public function beforeTraverse(Node $node): void
    {
    }

    public function enterNode(Node $node): int|Node|null
    {
        return null;
    }

    /**
     * @return null|int|\PhpParser\Node|Node[] Replacement node (or special return)
     */
    public function leaveNode(Node $node): int|\PhpParser\Node|array|null
    {
        return null;
    }

    public function afterTraverse(Node $node): void
    {
    }
}
