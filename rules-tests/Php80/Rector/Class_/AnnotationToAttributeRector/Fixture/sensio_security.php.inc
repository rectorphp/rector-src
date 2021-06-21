<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class SensioSecurity
{
    /**
     * @Security("is_granted('ROLE_USER')")
     */
    public function action()
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class SensioSecurity
{
    #[\Sensio\Bundle\FrameworkExtraBundle\Configuration\Security('is_granted(\'ROLE_USER\')')]
    public function action()
    {
    }
}

?>
