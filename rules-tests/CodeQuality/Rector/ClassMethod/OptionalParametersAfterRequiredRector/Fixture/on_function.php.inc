<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\OptionalParametersAfterRequiredRector\Fixture;

function foo($optional = 1, $required)
{
}

foo($optional, $required);

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\OptionalParametersAfterRequiredRector\Fixture;

function foo($required, $optional = 1)
{
}

foo($required, $optional);

?>
