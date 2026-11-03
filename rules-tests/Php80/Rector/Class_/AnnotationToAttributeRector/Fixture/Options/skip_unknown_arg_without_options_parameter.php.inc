<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Options;

use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\Options\ConstraintWithoutOptions;

final class SkipUnknownArgWithoutOptionsParameter
{
    /**
     * @ConstraintWithoutOptions(min=1, other=true)
     */
    public $description;
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Options;

use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\Options\ConstraintWithoutOptions;

final class SkipUnknownArgWithoutOptionsParameter
{
    #[ConstraintWithoutOptions(min: 1, other: true)]
    public $description;
}

?>
