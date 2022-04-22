<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Doctrine;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

final class MyClass
{
    /**
     * @ORM\ManyToOne(targetEntity="MyOtherClass")
     */
    private readonly Collection $items;
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Doctrine;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

final class MyClass
{
    #[ORM\ManyToOne(targetEntity: MyOtherClass::class)]
    private readonly Collection $items;
}
?>
