<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Symfony;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

final class ParamConverterWithSilentKey
{
    /**
     * @ParamConverter(name="some_name")
     */
    public function action()
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Symfony;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

final class ParamConverterWithSilentKey
{
    #[ParamConverter('some_name')]
    public function action()
    {
    }
}

?>
