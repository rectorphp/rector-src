<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Symfony;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

final class SymfonySecurity
{
    /**
     * @Security("is_granted(constant('\\Rector\\Tests\\Php80\\Rector\\Class_\\AnnotationToAttributeRector\\Source\\ConstantReference::FIRST_NAME'))")
     */
    public function action ()
    {
    }
}

?>
    -----
<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Symfony;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

final class SymfonySecurity
{
    #[Security("is_granted(constant('\\\Rector\\\Tests\\\Php80\\\Rector\\\Class_\\\AnnotationToAttributeRector\\\Source\\\ConstantReference::FIRST_NAME'))")]
    public function action ()
    {
    }
}

?>
