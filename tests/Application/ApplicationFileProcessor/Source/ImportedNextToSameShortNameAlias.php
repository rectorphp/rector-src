<?php

declare(strict_types=1);

namespace Rector\Tests\Application\ApplicationFileProcessor\Source;

use PhpParser\Node\Expr\BinaryOp\Plus;
use PhpParser\Node\Expr\AssignOp\Plus as AssignPlus;

final class ImportedNextToSameShortNameAlias
{
    public function run(Plus $plus, AssignPlus $assignPlus): void
    {
    }
}
