<?php

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted('TEST')
 */
class IsGrantedController extends AbstractController
{
}

?>
-----
<?php

use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @IsGranted('TEST')
 */
class IsGrantedController extends AbstractController
{
}

?>
