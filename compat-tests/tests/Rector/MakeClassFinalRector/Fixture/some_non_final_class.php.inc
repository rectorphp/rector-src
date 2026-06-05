<?php

namespace Rector\RectorCompatTests\Tests\Rector\MakeClassFinalRector\Fixture;

class SomeNonFinalClass
{
}

?>
-----
<?php

namespace Rector\RectorCompatTests\Tests\Rector\MakeClassFinalRector\Fixture;

final class SomeNonFinalClass
{
}

?>
