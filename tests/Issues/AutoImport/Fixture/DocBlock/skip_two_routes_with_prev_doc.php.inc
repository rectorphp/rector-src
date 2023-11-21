<?php

namespace Rector\Core\Tests\Issues\AutoImport\Fixture\DocBlock;

class TwoRoutesWithPrevDoc
{
    /**
     * @return Response
     * @\Symfony\Component\Routing\Annotation\Route("/first", methods={"GET"})
     * @\Symfony\Component\Routing\Annotation\Route("/second", methods={"GET"})
     */
    public function some(): Response
    {
        return new Response();
    }
}
