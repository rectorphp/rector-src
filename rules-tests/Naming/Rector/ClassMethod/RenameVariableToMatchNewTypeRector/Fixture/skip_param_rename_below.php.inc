<?php

namespace Rector\Tests\Naming\Rector\ClassMethod\RenameVariableToMatchNewTypeRector\Fixture;

use PhpParser\Node\Expr\Assign;

final class SkipParamRenameBelow
{
    public function run($assign)
    {
        $jsonDataAssign = new Assign(1, 2);
        $this->addNodeBeforeNode($jsonDataAssign, $assign);
    }
}
