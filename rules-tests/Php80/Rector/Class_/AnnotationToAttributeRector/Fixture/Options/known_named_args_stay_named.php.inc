<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Options;

use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\Options\ConstraintWithOptions;

final class KnownNamedArgsStayNamed
{
    /**
     * @ConstraintWithOptions(min=1, max=400)
     */
    public $description;
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Options;

use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\Options\ConstraintWithOptions;

final class KnownNamedArgsStayNamed
{
    #[ConstraintWithOptions(min: 1, max: 400)]
    public $description;
}

?>
