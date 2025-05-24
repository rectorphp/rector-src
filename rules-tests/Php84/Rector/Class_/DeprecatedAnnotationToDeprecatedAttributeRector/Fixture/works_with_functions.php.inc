<?php

namespace Rector\Tests\Php84\Rector\Class_\DeprecatedAnnotationToDeprecatedAttributeRector;

/**
 * @deprecated some reason.
 */
function works_with_functions()
{
}

?>
-----
<?php

namespace Rector\Tests\Php84\Rector\Class_\DeprecatedAnnotationToDeprecatedAttributeRector;

#[\Deprecated(message: 'some reason.')]
function works_with_functions()
{
}

?>
