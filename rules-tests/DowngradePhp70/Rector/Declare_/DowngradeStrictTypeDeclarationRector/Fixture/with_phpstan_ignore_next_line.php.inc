<?php

namespace Rector\Tests\DowngradePhp70\Rector\Declare_\DowngradeStrictTypeDeclarationRector\Fixture;

// @phpstan-ignore-next-line
declare(strict_types=1);

class Fixture
{
}

?>
-----
<?php

namespace Rector\Tests\DowngradePhp70\Rector\Declare_\DowngradeStrictTypeDeclarationRector\Fixture;

class Fixture
{
}

?>
