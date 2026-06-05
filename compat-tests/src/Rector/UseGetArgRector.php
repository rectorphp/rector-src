<?php

declare(strict_types=1);

namespace Rector\RectorCompatTests\Rector;

use PhpParser\Modifiers;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Function_;
use Rector\Rector\AbstractRector;

final class UseGetArgRector extends AbstractRector
{
    /**
     * @return array<class-string<Class_>>
     */
    public function getNodeTypes(): array
    {
        return [FuncCall::class];
    }

    /**
     * @param FuncCall $node
     */
    public function refactor(Node $node)
    {
        // here we should load Rector's php-parser 5.6, that already has getArg() method
        $firstArg = $node->getArg('', 0);
        if (! $firstArg instanceof Arg) {
            return null;;
        }

        $firstArg->value = new String_('changed_value');

        return $node;
    }
}
