<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Symfony\Validator;

use Symfony\Component\Validator\Constraints as Assert;

final class ValidationIntegerParameter
{
    /**
     * @Assert\Length(
     *     min="100",
     *     max="255",
     *     maxMessage="some Message",
     *     allowed="true"
     * )
     */
    public function action()
    {
    }

    /**
     * @Assert\Length(
     *      min=100,
     *      max=255,
     *      maxMessage="some Message",
     *      allowed=true
     *  )
     */
    public function anotherAction()
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Symfony\Validator;

use Symfony\Component\Validator\Constraints as Assert;

final class ValidationIntegerParameter
{
    #[Assert\Length(min: 100, max: 255, maxMessage: 'some Message', allowed: 'true')]
    public function action()
    {
    }

    #[Assert\Length(min: 100, max: 255, maxMessage: 'some Message', allowed: true)]
    public function anotherAction()
    {
    }
}

?>
