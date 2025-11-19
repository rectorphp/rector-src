<?php

declare(strict_types=1);

namespace Rector\PhpParser\Node\CustomNode;

use PhpParser\Node\Stmt;
use PhpParser\NodeAbstract;

final class StmtsAwareNode extends Stmt
{
    public function __construct(
        public \PhpParser\Node $originalNode
    ) {
        parent::__construct();
    }

    public function getType(): string
    {
        return 'Node_StmtsAware';
    }

    /**
     * @var string
     */
    public function getSubNodeNames(): array
    {
        // empty on purpose to avoid infinity loop
        return [];
    }

    public function getOriginalNode(): \PhpParser\Node
    {
        return $this->originalNode;
    }
}
