<?php

namespace Rector\Core\Tests\Issues\RenameAnnotationToAttributeAutoImport\Fixture;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("TEST")
 */
#[Route(path: '/pro/{id}/networks/{networkId}/sectors', name: 'api_network_sectors', requirements: ['id' => '\d+', 'networkId' => '\d+'])]
class WithExistingAttribute extends AbstractController
{
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\RenameAnnotationToAttributeAutoImport\Fixture;

use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/pro/{id}/networks/{networkId}/sectors', name: 'api_network_sectors', requirements: ['id' => '\d+', 'networkId' => '\d+'])]
#[IsGranted('TEST')]
class WithExistingAttribute extends AbstractController
{
}

?>
