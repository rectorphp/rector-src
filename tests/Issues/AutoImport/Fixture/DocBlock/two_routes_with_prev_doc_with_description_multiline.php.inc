<?php

namespace Rector\Tests\Issues\AutoImport\Fixture\DocBlock;

class TwoRoutesWithPrevDocWithDescriptionMultiline
{
    /**
     * @return Response line 1
     *     line 2
     *      line 3
     * @\Symfony\Component\Routing\Annotation\Route("/first", methods={"GET"})
     * @\Symfony\Component\Routing\Annotation\Route("/second", methods={"GET"})
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
class TwoRoutesWithPrevDocWithDescriptionMultiline
{
    /**
     * @return Response line 1
     *     line 2
     *      line 3
     * @Route("/first", methods={"GET"})
     * @Route("/second", methods={"GET"})
     */
    public function some(): Response
    {
        return new Response();
    }
}

?>
