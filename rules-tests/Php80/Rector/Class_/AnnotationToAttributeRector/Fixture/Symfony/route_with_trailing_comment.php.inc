<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Symfony;

use Symfony\Component\Routing\Annotation\Route;

final class RouteWithTrailingComment
{
    /**
     * @Route("/path", name="action")
     * this comment must be kept
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

final class RouteWithTrailingComment
{
    /**
     * this comment must be kept
     */
    #[Route(path: '/path', name: 'action')]
    public function action()
    {
    }
}

?>
