<?php

namespace Rector\Core\Tests\Issues\AutoImport\Fixture\DocBlock;

class TwoRoutesAfterGeneric
{
    /**
     * @OA\Property(
     *     type="array",
     *     @OA\Items(ref=@Model(type=TestItem::class))
     * )
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

namespace Rector\Core\Tests\Issues\AutoImport\Fixture\DocBlock;

use Symfony\Component\Routing\Annotation\Route;
class TwoRoutesAfterGeneric
{
    /**
     * @OA\Property(
     *     type="array",
     *     @OA\Items(ref=@Model(type=TestItem::class))
     * )
     * @Route("/first", methods={"GET"})
     * @Route("/second", methods={"GET"})
     */
    public function some(): Response
    {
        return new Response();
    }
}

?>
