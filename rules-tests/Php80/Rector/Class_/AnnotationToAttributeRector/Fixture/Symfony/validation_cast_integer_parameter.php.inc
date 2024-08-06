<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Symfony;

use Symfony\Component\Validator\Constraints as Assert;

final class ValidationCastIntegerParameter
{
    /**
     * @Assert\Length(
     *     min="100",
     *     max="255"
     * )
     */
    public function action()
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Symfony;

use Symfony\Component\Validator\Constraints as Assert;

final class ValidationCastIntegerParameter
{
    #[Assert\Length(min: 100, max: 255)]
    public function action()
    {
    }
}

?>
