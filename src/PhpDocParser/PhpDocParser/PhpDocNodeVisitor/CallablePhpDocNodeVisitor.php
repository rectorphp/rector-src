<?php

declare(strict_types=1);

namespace Rector\PhpDocParser\PhpDocParser\PhpDocNodeVisitor;

use PHPStan\PhpDocParser\Ast\AbstractNodeVisitor;
use PHPStan\PhpDocParser\Ast\Node;
use PHPStan\PhpDocParser\Ast\NodeTraverser;

final class CallablePhpDocNodeVisitor extends AbstractNodeVisitor
{
    /**
     * @var callable(Node): (NodeTraverser::*|Node|null)
     */
    private $callable;

    /**
     * @param callable(Node $node): (NodeTraverser::*|Node|null) $callable
     */
    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    public function enterNode(Node $node): int|Node|null
    {
        $callable = $this->callable;
        return $callable($node);
    }
}
