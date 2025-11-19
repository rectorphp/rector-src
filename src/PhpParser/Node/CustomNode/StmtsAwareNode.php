<?php

declare(strict_types=1);

namespace Rector\PhpParser\Node\CustomNode;

use PhpParser\Node\Stmt;
use Rector\Contract\PhpParser\Node\StmtsAwareInterface;

final class StmtsAwareNode extends Stmt implements StmtsAwareInterface
{
    public function __construct(
        public Stmt\If_ $originalNode
    ) {
        parent::__construct();
    }

    public function getType(): string
    {
        return 'Rector_Node_StmtsAware';
    }

    public function &__get($property)
    {
        if ($property === 'stmts') {
            return $this->originalNode->stmts;
        }

        return $this->{$property};
    }

    /**
     * @var string[]
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
