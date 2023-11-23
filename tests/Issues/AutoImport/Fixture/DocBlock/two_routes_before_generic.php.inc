<?php

namespace Rector\Core\Tests\Issues\AutoImport\Fixture\DocBlock;

class TwoRoutesBeforeGeneric
{
    /**
     * @\Symfony\Component\Routing\Annotation\Route("/first", methods={"GET"})
     * @\Symfony\Component\Routing\Annotation\Route("/second", methods={"GET"})
     * @OA\Property(
     *     type="array",
     *     @OA\Items(ref=@Model(type=TestItem::class))
     * )
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
class TwoRoutesBeforeGeneric
{
    /**
     * @Route("/first", methods={"GET"})
     * @Route("/second", methods={"GET"})
     * @OA\Property(
     *     type="array",
     *     @OA\Items(ref=@Model(type=TestItem::class))
     * )
     */
    public function some(): Response
    {
        return new Response();
    }
}

?>
