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

final class FromSimpleTag
{
    #[\Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\Attribute\NewName1(limit: 10, period: 100)]
    #[\Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\Attribute\NewName1(limit: 20, period: 200)]
    #[\Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\Attribute\NewName2(action: 'login')]
    #[\Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\Attribute\SameName('non_named_arg')]
    public function someStep(): void
    {
    }
}

?>
