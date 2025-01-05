<?php

namespace Rector\Tests\Issues\AddClassDependency\Fixture;

use Rector\Tests\Issues\AddClassDependency\Source\SomeAutowiredService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PreferRequiredSetter extends Controller
{
    private SomeAutowiredService $someAutowiredService;

    /**
     * @required
     */
    public function autowire(
        SomeAutowiredService $someAutowiredService,
    ) {
        $this->someAutowiredService = $someAutowiredService;
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

class PreferRequiredSetter extends Controller
{
    private SomeAutowiredService $someAutowiredService;
    private \Symfony\Component\Validator\Validator\ValidatorInterface $validator;

    /**
     * @required
     */
    public function autowire(
        SomeAutowiredService $someAutowiredService, \Symfony\Component\Validator\Validator\ValidatorInterface $validator,
    ) {
        $this->someAutowiredService = $someAutowiredService;
        $this->validator = $validator;
    }

    public function configure()
    {
        $someType = $this->validator;
    }
}

?>
