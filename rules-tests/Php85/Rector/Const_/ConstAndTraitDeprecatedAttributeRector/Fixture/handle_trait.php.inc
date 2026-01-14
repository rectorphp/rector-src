<?php

namespace Rector\Tests\Php85\Rector\Const_\ConstAndTraitDeprecatedAttributeRector;

/**
 * @deprecated avoid using
 */
trait HandleTrait
{
}

?>
-----
<?php

namespace Rector\Tests\Php85\Rector\Const_\ConstAndTraitDeprecatedAttributeRector;

#[\Deprecated(message: 'avoid using')]
trait HandleTrait
{
}

?>
