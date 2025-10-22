<?php

namespace Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\Fixture;

final class WithAttributeOnPropertyPromotionInline
{
	private function __construct(
        #[MyAttr]private string $id
    ){}
}

?>
-----
<?php

namespace Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\Fixture;

final class WithAttributeOnPropertyPromotionInline
{
	private function __construct(
        #[MyAttr]private readonly string $id
    ){}
}

?>
