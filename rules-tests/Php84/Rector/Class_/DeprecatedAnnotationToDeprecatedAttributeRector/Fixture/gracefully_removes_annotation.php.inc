<?php

namespace Rector\Tests\Php84\Rector\Class_\DeprecatedAnnotationToDeprecatedAttributeRector;

final class GracefullyRemovesAnnotation
{
    /**
     * @deprecated some reason.
     * @see https://getrector.com
     */
    public const FOO = 'foo';
}

?>
-----
<?php

namespace Rector\Tests\Php84\Rector\Class_\DeprecatedAnnotationToDeprecatedAttributeRector;

final class GracefullyRemovesAnnotation
{
    /**
     * @see https://getrector.com
     */
    #[\Deprecated(message: 'some reason.')]
    public const FOO = 'foo';
}

?>
