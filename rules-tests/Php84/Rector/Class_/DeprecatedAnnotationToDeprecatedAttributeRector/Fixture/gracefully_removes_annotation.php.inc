<?php

namespace Rector\Tests\Php84\Rector\Class_\DeprecatedAnnotationToDeprecatedAttributeRector;

/**
 * @deprecated some reason.
 * @see https://getrector.com
 */
final class GracefullyRemovesAnnotation
{
}

?>
-----
<?php

namespace Rector\Tests\Php84\Rector\Class_\DeprecatedAnnotationToDeprecatedAttributeRector;

/**
 * @see https://getrector.com
 */
#[\Deprecated(message: 'some reason.')]
final class GracefullyRemovesAnnotation
{
}

?>
