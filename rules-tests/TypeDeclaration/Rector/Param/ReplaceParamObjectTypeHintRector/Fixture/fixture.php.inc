<?php

namespace Rector\Tests\TypeDeclaration\Rector\Param\ReplaceParamObjectTypeHintRector;

class Fixture
{
    public function call(\Carbon\Carbon $carbon)
    {

    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Param\ReplaceParamObjectTypeHintRector;

class Fixture
{
    public function call(\Carbon\CarbonInterface $carbon)
    {

    }
}

?>
