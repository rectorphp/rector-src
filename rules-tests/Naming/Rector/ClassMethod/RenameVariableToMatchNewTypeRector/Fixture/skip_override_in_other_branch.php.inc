<?php

namespace Rector\Tests\Naming\Rector\ClassMethod\RenameVariableToMatchNewTypeRector\Fixture;

use Nette\Utils\Strings;
use PhpParser\Node\Expr\Cast\String_;
use PhpParser\Node\Scalar\Int_;

class SkipOverrideInOtherBranch
{
    public function run(string $serviceType)
    {
        if (Strings::contains($serviceType, '_') && ! Strings::contains($serviceType, '\\')) {
            $getArgumentValue = new String_($serviceType);
        }  else {
            $getArgumentValue = new Int_(100);
        }

        return $getArgumentValue;
    }
}
