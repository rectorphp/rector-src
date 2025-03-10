<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture;

final class FromSimpleTag
{
    /**
     * @OldName1(limit=10, period=100)
     * @OldName1(limit=20, period=200)
     * @OldName2(action=login)
     * @SameName(non_named_arg)
     */
    public function someStep(): void
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture;

use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\Attribute\NewName1;
use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\Attribute\NewName2;
use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\Attribute\SameName;

final class FromSimpleTag
{
    #[\Name\Space\NewName1(limit: 10, period: 100)]
    #[\Name\Space\NewName1(limit: 20, period: 200)]
    #[\Name\Space\NewName2(action: 'login')]
    #[\Name\Space\SameName('non_named_arg')]
    public function someStep(): void
    {
    }
}

?>
