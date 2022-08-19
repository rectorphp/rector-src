<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture;

use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source as Assert;

final class EntityColumnAndAssertChoice
{
    /**
     * @Assert\GenericAnnotation({"php5", "php7"})
     * @Assert\GenericAnnotation(choices={"5.0", "id"="5.2"})
     * @Assert\GenericAnnotation(choices={2, 3, 5})
     */
    public $primeNumbers;
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture;

use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source as Assert;

final class EntityColumnAndAssertChoice
{
    #[Assert\GenericAnnotation(['php5', 'php7'])]
    #[Assert\GenericAnnotation(choices: ['5.0', 'id' => '5.2'])]
    #[Assert\GenericAnnotation(choices: [2, 3, 5])]
    public $primeNumbers;
}

?>
