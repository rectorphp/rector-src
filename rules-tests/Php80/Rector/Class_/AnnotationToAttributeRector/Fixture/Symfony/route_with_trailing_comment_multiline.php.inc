<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Symfony;

use Symfony\Component\Routing\Annotation\Route;

final class RouteWithTrailingCommentMultiline
{
    /**
     * @Route(
     *     "/path",
     *     name="action"
     * )
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

final class RouteWithTrailingCommentMultiline
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
