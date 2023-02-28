<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\FixturePhp82;

final class FalseDefaultValue
{
    private $isEnabled = false;
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\FixturePhp82;

final class FalseDefaultValue
{
    private false $isEnabled = false;
}

?>
