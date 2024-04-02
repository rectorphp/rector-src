<?php

namespace Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\Fixture;

final class WithReadonlyDocOnPropertyPromotion
{
	private function __construct(
        /** @readonly */
        private string $id
    ){}
}

?>
-----
<?php

namespace Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\Fixture;

final class WithReadonlyDocOnPropertyPromotion
{
	private function __construct(
        private readonly string $id
    ){}
}

?>
