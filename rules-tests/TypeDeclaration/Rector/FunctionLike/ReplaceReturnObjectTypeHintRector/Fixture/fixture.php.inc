<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\ReplaceReturnObjectTypeHintRector;

class Fixture
{
    public function call(): \Carbon\Carbon
    {

    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\ReplaceReturnObjectTypeHintRector;

class Fixture
{
    public function call(): \Carbon\CarbonInterface
    {

    }
}

?>
