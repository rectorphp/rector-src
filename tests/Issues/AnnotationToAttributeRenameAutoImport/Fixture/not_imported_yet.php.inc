<?php

namespace Rector\Tests\Issues\AnnotationToAttributeRenameAutoImport\Fixture;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @\Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted("TEST")
 */
class NotImportedYet extends AbstractController
{
}

?>
-----
<?php

namespace Rector\Tests\Issues\AnnotationToAttributeRenameAutoImport\Fixture;

use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('TEST')]
class NotImportedYet extends AbstractController
{
}

?>
