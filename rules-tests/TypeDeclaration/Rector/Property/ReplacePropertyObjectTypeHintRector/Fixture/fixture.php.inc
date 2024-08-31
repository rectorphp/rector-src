<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\ReplacePropertyObjectTypeHintRector;

class Fixture
{
    protected \Carbon\Carbon $carbon;
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\ReplacePropertyObjectTypeHintRector;

class Fixture
{
    protected \Carbon\CarbonInterface $carbon;
}

?>
