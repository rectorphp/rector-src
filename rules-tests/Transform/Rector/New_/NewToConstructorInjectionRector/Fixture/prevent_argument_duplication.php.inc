<?php

namespace Rector\Tests\Transform\Rector\New_\NewToConstructorInjectionRector\Fixture;

use Rector\Tests\Transform\Rector\New_\NewToConstructorInjectionRector\Source\DummyValidator;

class Foo {
    private DummyValidator $dummyValidator;
    public function __construct(DummyValidator $dummyValidator)
    {
        $this->dummyValidator = $dummyValidator;
    }
}

class Bar extends Foo {
    public function run()
    {
        $dummyValidator = new DummyValidator();
        $dummyValidator->validate(100000);
    }
}

?>
-----
<?php

namespace Rector\Tests\Transform\Rector\New_\NewToConstructorInjectionRector\Fixture;

use Rector\Tests\Transform\Rector\New_\NewToConstructorInjectionRector\Source\DummyValidator;

class Foo {
    private DummyValidator $dummyValidator;
    public function __construct(DummyValidator $dummyValidator)
    {
        $this->dummyValidator = $dummyValidator;
    }
}

class Bar extends Foo {
    public function __construct(private \Rector\Tests\Transform\Rector\New_\NewToConstructorInjectionRector\Source\DummyValidator $dummyValidator)
    {
        parent::__construct($dummyValidator);
    }
    public function run()
    {
        $this->dummyValidator->validate(100000);
    }
}

?>
