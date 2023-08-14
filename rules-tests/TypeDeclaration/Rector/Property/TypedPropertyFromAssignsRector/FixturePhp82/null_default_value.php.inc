<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\FixturePhp82;

final class NullDefaultValue
{
    private $isEnabled = null;
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\FixturePhp82;

final class NullDefaultValue
{
    private null $isEnabled = null;
}

?>
