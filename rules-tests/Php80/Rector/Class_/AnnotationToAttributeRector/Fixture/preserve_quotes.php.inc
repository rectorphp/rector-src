<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture;

use Symfony\Component\Validator\Constraints as Assert;

final class MyEntity
{
    /**
     * @Assert\Choice(callback="\MyApp\Path\To\My::callbackFunction", strict=true)
     */
    private string $type;
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture;

use Symfony\Component\Validator\Constraints as Assert;

final class MyEntity
{
    #[Assert\Choice(callback: '\MyApp\Path\To\My::callbackFunction', strict: true)]
    private string $type;
}

?>
