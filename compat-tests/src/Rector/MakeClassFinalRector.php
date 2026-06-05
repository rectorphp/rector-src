<?php

declare(strict_types=1);

namespace Rector\RectorCompatTests\Rector;

use PhpParser\Modifiers;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use Rector\Rector\AbstractRector;

final class MakeClassFinalRector extends AbstractRector
{
    /**
     * @return array<class-string<Class_>>
     */
    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /**
     * @param Class_ $node
     */
    public function refactor(Node $node)
    {
        if ($node->isFinal()) {
            return null;
        }

        $node->flags |= Modifiers::FINAL;

        return $node;
    }
}
