<?php

declare(strict_types=1);

namespace Rector\Core\PhpParser\Node\Value;

use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\Ternary;
use PhpParser\Node\Name;
use Rector\Core\PhpParser\Printer\BetterStandardPrinter;

final class TernaryBracketWrapper
{
    public function __construct(private BetterStandardPrinter $betterStandardPrinter)
    {
    }

    public function getWrappedTernaryConstFetch(Ternary $ternary): ConstFetch
    {
        return new ConstFetch(new Name('(' . $this->betterStandardPrinter->print($ternary) . ')'));
    }
}
