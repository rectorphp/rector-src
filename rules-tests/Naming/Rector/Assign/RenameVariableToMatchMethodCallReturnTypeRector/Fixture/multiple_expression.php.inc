<?php

namespace Rector\Tests\Naming\Rector\Assign\RenameVariableToMatchMethodCallReturnTypeRector\Fixture;

use Rector\Tests\Naming\Rector\Assign\RenameVariableToMatchMethodCallReturnTypeRector\Source\FastRunner;
use Rector\Tests\Naming\Rector\Assign\RenameVariableToMatchMethodCallReturnTypeRector\Source\FullName;

class MultipleExpression
{
    public function run()
    {
        $a = $this->getFastRunner();
        $a->run();

        $b = $this->getFullName();
        $b->getName();
    }

    public function getFastRunner(): FastRunner
    {
        return new FastRunner();
    }

    public function getFullName(): FullName
    {
        return new FullName();
    }
}

?>
-----
<?php

namespace Rector\Tests\Naming\Rector\Assign\RenameVariableToMatchMethodCallReturnTypeRector\Fixture;

use Rector\Tests\Naming\Rector\Assign\RenameVariableToMatchMethodCallReturnTypeRector\Source\FastRunner;
use Rector\Tests\Naming\Rector\Assign\RenameVariableToMatchMethodCallReturnTypeRector\Source\FullName;

class MultipleExpression
{
    public function run()
    {
        $fastRunner = $this->getFastRunner();
        $fastRunner->run();

        $fullName = $this->getFullName();
        $fullName->getName();
    }

    public function getFastRunner(): FastRunner
    {
        return new FastRunner();
    }

    public function getFullName(): FullName
    {
        return new FullName();
    }
}

?>
