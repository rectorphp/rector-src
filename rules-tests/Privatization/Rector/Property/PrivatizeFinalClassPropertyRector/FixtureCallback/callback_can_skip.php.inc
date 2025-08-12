<?php

namespace Rector\Tests\Privatization\Rector\Property\PrivatizeFinalClassPropertyRector\Fixture;

final class FinalClassWithProtectedProperties
{
    protected string $value;
    protected string $shouldBeProtected;

    public function __construct(
        protected string $bar,
        protected string $shouldBeProtectedAlso,
    ) {}
}

?>
-----
<?php

namespace Rector\Tests\Privatization\Rector\Property\PrivatizeFinalClassPropertyRector\Fixture;

final class FinalClassWithProtectedProperties
{
    private string $value;
    protected string $shouldBeProtected;

    public function __construct(
        private string $bar,
        protected string $shouldBeProtectedAlso,
    ) {}
}

?>
