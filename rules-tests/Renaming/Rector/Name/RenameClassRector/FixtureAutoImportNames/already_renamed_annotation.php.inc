<?php

use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @\Symfony\Component\Security\Http\Attribute\IsGranted('TEST')
 */
class IsGrantedController2 extends AbstractController
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
class IsGrantedController2 extends AbstractController
{
}

?>

?>
