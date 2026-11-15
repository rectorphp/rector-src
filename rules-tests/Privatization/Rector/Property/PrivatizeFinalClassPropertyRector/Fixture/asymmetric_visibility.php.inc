<?php

namespace Rector\Tests\Privatization\Rector\Property\PrivatizeFinalClassPropertyRector\Fixture;

final class AsymmetricVisibility
{
    protected protected(set) string $name = 'a';

    protected private(set) string $other = 'b';

    public function __construct(
        protected protected(set) int $promoted,
    ) {
    }
}

?>
-----
<?php

namespace Rector\Tests\Privatization\Rector\Property\PrivatizeFinalClassPropertyRector\Fixture;

final class AsymmetricVisibility
{
    private string $name = 'a';

    private string $other = 'b';

    public function __construct(
        private int $promoted,
    ) {
    }
}

?>
