<?php

namespace Rector\Tests\Issues\AddClassDependency\Fixture;

use Rector\Tests\Issues\AddClassDependency\Source\SomeAutowiredService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

final class StickWithConstructor extends Controller
{
    public function __construct(
        private SomeAutowiredService $someAutowiredService,
    ) {
    }

    public function configure()
    {
        $someType = $this->get('validator');
    }
}

?>
-----
<?php

namespace Rector\Tests\Issues\AddClassDependency\Fixture;

use Rector\Tests\Issues\AddClassDependency\Source\SomeAutowiredService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

final class StickWithConstructor extends Controller
{
    public function __construct(
        private SomeAutowiredService $someAutowiredService, private readonly \Symfony\Component\Validator\Validator\ValidatorInterface $validator,
    ) {
    }

    public function configure()
    {
        $someType = $this->validator;
    }
}

?>
