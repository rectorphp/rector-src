<?php

namespace Rector\Tests\Php84\Rector\Class_\DeprecatedAnnotationToDeprecatedAttributeRector;

/**
 * @deprecated some reason.
 */
trait WorksWithTraits
{
    /**
     * @deprecated use new constant.
     */
    public const CONSTANT = 'some reason.';

    /**
     * @deprecated use new property.
     */
    public $property;

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

#[\Deprecated(message: 'some reason.')]
trait WorksWithTraits
{
    #[\Deprecated(message: 'use new constant.')]
    public const CONSTANT = 'some reason.';

    #[\Deprecated(message: 'use new property.')]
    public $property;

    #[\Deprecated(message: 'use new method.', since: '1.0.1')]
    public function run()
    {
    }
}

?>
