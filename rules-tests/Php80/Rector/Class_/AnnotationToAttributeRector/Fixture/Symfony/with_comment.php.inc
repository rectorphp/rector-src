<?php

namespace Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Fixture\Symfony;

use Symfony\Component\Routing\Annotation\Route;

final class WithComment
{
    /**
     * This is an example comment
     *
	 * @Route("/test", name="test_route")
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

final class WithComment
{
    /**
     * This is an example comment
     */
    #[Route(path: '/test', name: 'test_route')]
    public function action()
    {
    }
}

?>
