<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\FixturePhp82;

final class TrueDefaultValue
{
    private $isEnabled = true;
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\FixturePhp82;

final class TrueDefaultValue
{
    private true $isEnabled = true;
}

?>
