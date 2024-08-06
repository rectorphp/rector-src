<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Symfony;

use Symfony\Component\Validator\Constraints as Assert;

final class ValidationFile
{
    /**
     * @Assert\File(maxSize="100")
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

final class ValidationFile
{
    #[Assert\File(maxSize: 100)]
    public function action()
    {
    }
}

?>
