<?php

namespace Rector\Tests\Php84\Rector\Class_\DeprecatedAnnotationToDeprecatedAttributeRector;

final class Fixture
{
    /**
     * @deprecated use new constant.
     */
    public const CONSTANT = 'some reason.';

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

final class Fixture
{
    #[\Deprecated(message: 'use new constant.')]
    public const CONSTANT = 'some reason.';

    #[\Deprecated(message: 'use new method.', since: '1.0.1')]
    public function run()
    {
    }
}

?>
