<?php

namespace Rector\Core\Tests\Issues\AnnotationToAttributeRenameAutoImport\Fixture;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted("TEST")
 */
class IsGrantedController extends AbstractController
{
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\AnnotationToAttributeRenameAutoImport\Fixture;

use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('TEST')]
class IsGrantedController extends AbstractController
{
}

?>
