<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector\Source\AbstractParentWithFilledProperty;

final class OwnPropertyWithParentConstruct extends AbstractParentWithFilledProperty
{
    private array $ownTypes;

    public function __construct()
    {
        parent::__construct();

        $this->ownTypes = ['refresh_token'];
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector\Fixture;

use Rector\Tests\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector\Source\AbstractParentWithFilledProperty;

final class OwnPropertyWithParentConstruct extends AbstractParentWithFilledProperty
{
    private array $ownTypes = ['refresh_token'];

    public function __construct()
    {
        parent::__construct();
    }
}

?>
