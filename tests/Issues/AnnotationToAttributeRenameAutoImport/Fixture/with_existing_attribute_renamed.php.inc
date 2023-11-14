<?php

namespace Rector\Core\Tests\Issues\RenameAnnotationToAttributeAutoImport\Fixture;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/pro/{id}/networks/{networkId}/sectors', name: 'api_network_sectors', requirements: ['id' => '\d+', 'networkId' => '\d+'])]
#[\Symfony\Component\Security\Http\Attribute\IsGranted('TEST')]
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
