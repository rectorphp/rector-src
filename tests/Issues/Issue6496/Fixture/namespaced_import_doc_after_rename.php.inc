<?php

namespace App;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/someUrl")
 */
class SomeController extends Controller
{
}

?>
-----
<?php

namespace App;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/someUrl")
 */
class SomeController extends Controller
{
}

?>
