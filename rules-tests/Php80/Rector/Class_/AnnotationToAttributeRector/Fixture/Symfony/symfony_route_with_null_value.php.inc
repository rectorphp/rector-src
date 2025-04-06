<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Symfony;

use Symfony\Component\Routing\Annotation\Route;

final class SymfonyRouteWithNullValue
{
    /**
     * @Route("/some/{id}", defaults={"id" = null})
     */
    public function action()
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Symfony;

use Symfony\Component\Routing\Annotation\Route;

final class SymfonyRouteWithNullValue
{
    #[Route(path: '/some/{id}', defaults: ['id' => null])]
    public function action()
    {
    }
}

?>
