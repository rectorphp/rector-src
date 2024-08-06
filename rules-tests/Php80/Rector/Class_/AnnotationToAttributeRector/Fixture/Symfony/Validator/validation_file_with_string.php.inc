<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Symfony;

use Symfony\Component\Validator\Constraints as Assert;

final class ValidationFileWithString
{
    /**
     * @Assert\File(maxSize="5555K")
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

final class ValidationFileWithString
{
    #[Assert\File(maxSize: '5555K')]
    public function action()
    {
    }
}

?>
