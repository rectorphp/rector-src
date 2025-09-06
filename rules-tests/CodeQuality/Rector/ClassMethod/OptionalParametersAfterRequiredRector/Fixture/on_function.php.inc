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

function foo($optional = 1, $required = null)
{
}

foo($optional, $required);

?>
