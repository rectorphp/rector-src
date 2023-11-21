<?php

namespace Rector\Core\Tests\Issues\AutoImport\Fixture\DocBlock;

class TwoRoutesWithNextDoc2
{
    /**
     * @\Symfony\Component\Routing\Annotation\Route("/first", methods={"GET"})
     * @\Symfony\Component\Routing\Annotation\Route("/second", methods={"GET"})
     * @return Response
     * @\Symfony\Component\Routing\Annotation\Route("/third", methods={"GET"})
     */
    public function some(): Response
    {
        return new Response();
    }
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\AutoImport\Fixture\DocBlock;

use Symfony\Component\Routing\Annotation\Route;
class TwoRoutesWithNextDoc2
{
    /**
     * @Route("/first", methods={"GET"})
     * @Route("/second", methods={"GET"})
     * @return Response
     * @\Symfony\Component\Routing\Annotation\Route("/third", methods={"GET"})
     */
    public function some(): Response
    {
        return new Response();
    }
}

?>