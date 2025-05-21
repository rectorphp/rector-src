<?php

namespace Rector\Tests\Php84\Rector\Class_\DeprecatedAnnotationToDeprecatedAttributeRector;

/**
 * @deprecated 1.0.0 some reason.
 */
final class Fixture
{
    /**
     * @deprecated 1.0.1 use new method.
     */
    public function run()
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Php84\Rector\Class_\DeprecatedAnnotationToDeprecatedAttributeRector;

#[\Deprecated(message: 'some reason.', since: '1.0.0')]
final class Fixture
{
    #[\Deprecated(message: 'use new method.', since: '1.0.1')]
    public function run()
    {
    }
}

?>
