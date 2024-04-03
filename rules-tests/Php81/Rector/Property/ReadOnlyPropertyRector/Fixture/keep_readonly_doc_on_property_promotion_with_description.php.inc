<?php

namespace Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\Fixture;

final class KeepReadonlyDocOnPropertyPromotionWithDescription
{
	private function __construct(
        /**
         * @readonly some desc
         */
        private string $id
    ){}
}

?>
-----
<?php

namespace Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\Fixture;

final class KeepReadonlyDocOnPropertyPromotionWithDescription
{
	private function __construct(
        /**
         * @readonly some desc
         */
        private readonly string $id
    ){}
}

?>
