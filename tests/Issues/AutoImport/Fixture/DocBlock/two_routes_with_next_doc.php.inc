<?php

namespace Rector\Tests\Issues\AutoImport\Fixture\DocBlock;

class TwoRoutesWithNextDoc
{
    /**
     * @\Symfony\Component\Routing\Annotation\Route("/first", methods={"GET"})
     * @\Symfony\Component\Routing\Annotation\Route("/second", methods={"GET"})
     * @return Response
     */
    public function some(): Response
    {
        return new Response();
    }
}

?>
-----
<?php

namespace Rector\Tests\Issues\AutoImport\Fixture\DocBlock;

use Symfony\Component\Routing\Annotation\Route;
class TwoRoutesWithNextDoc
{
    /**
     * @Route("/first", methods={"GET"})
     * @Route("/second", methods={"GET"})
     * @return Response
     */
    public function some(): Response
    {
        return new Response();
    }
}

?>
