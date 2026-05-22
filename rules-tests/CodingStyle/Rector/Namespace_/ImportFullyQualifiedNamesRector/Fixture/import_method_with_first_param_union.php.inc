<?php

namespace Rector\Tests\CodingStyle\Rector\Namespace_\ImportFullyQualifiedNamesRector\Fixture;

/**
 * @method static \Rector\Tests\CodingStyle\Rector\Namespace_\ImportFullyQualifiedNamesRector\Source\NormalParamClass disable(string|array ...$classes)
 */
class ImportMethodWithFirstParamUnion
{
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\Namespace_\ImportFullyQualifiedNamesRector\Fixture;

use Rector\Tests\CodingStyle\Rector\Namespace_\ImportFullyQualifiedNamesRector\Source\NormalParamClass;

/**
 * @method static NormalParamClass disable(string|array ...$classes)
 */
class ImportMethodWithFirstParamUnion
{
}

?>
