<?php

namespace Rector\Tests\Naming\Rector\ClassMethod\RenameVariableToMatchNewTypeRector\Fixture;

use PhpParser\Node\Expr\Assign;

final class SkipMethodCallRenameBelow
{
    public function run()
    {
        $assign = $this->getAssign();

        $jsonDataAssign = new Assign(1, 2);
        $this->addNodeBeforeNode($jsonDataAssign, $assign);
    }

    private function getAssign()
    {
        return new Assign();
    }
}
