<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture;

use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\Attribute\EmptyAttribute;

/**
 * @EmptyAttribute(foo="")
 */
class UseEmptyAttribute
{
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture;

use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\Attribute\EmptyAttribute;

#[\Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\Attribute\EmptyAttribute(foo: '')]
class UseEmptyAttribute
{
}

?>
