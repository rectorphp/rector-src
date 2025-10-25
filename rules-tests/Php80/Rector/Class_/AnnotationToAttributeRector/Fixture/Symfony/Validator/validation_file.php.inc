<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Symfony\Validator;

use Symfony\Component\Validator\Constraints as Assert;

final class ValidationFile
{
    /**
     * @Assert\File(maxSize="100")
     * @Assert\File(maxSize="5555K")
     */
    public function action()
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Symfony\Validator;

use Symfony\Component\Validator\Constraints as Assert;

final class ValidationFile
{
    #[Assert\File(maxSize: 100)]
    #[Assert\File(maxSize: '5555K')]
    public function action()
    {
    }
}

?>
