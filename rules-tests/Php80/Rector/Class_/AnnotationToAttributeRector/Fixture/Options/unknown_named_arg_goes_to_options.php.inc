<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Options;

use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\Options\ConstraintWithOptions;

final class UnknownNamedArgGoesToOptions
{
    /**
     * @ConstraintWithOptions(min=1, max=400, message="too long", allowEmptyString=true)
     */
    public $description;
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Options;

use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\Options\ConstraintWithOptions;

final class UnknownNamedArgGoesToOptions
{
    #[ConstraintWithOptions(min: 1, max: 400, message: 'too long', options: ['allowEmptyString' => true])]
    public $description;
}

?>
