<?php

namespace Rector\Tests\Php85\Rector\Const_\ConstAndTraitDeprecatedAttributeRector;

/**
 * @deprecated use new constant
 */
const CONSTANT = 'some reason';

/**
 * @deprecated 2.0.0 do not use
 */
const UNUSED = 'ignored';

?>
-----
<?php

namespace Rector\Tests\Php85\Rector\Const_\ConstAndTraitDeprecatedAttributeRector;

#[\Deprecated(message: 'use new constant')]
const CONSTANT = 'some reason';

#[\Deprecated(message: 'do not use', since: '2.0.0')]
const UNUSED = 'ignored';

?>
