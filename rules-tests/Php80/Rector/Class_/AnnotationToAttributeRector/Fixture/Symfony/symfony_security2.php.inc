<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Symfony;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

final class SymfonySecurity2
{
    /**
     * @Security("is_granted('ROLE_ADMIN') and is_granted('ROLE_FRIENDLY_USER')")
     */
    public function action()
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Symfony;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

final class SymfonySecurity2
{
    #[Security("is_granted('ROLE_ADMIN') and is_granted('ROLE_FRIENDLY_USER')")]
    public function action()
    {
    }
}

?>
