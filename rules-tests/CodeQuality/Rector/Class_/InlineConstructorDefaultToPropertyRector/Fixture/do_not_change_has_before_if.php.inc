<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector\Fixture;

final class DonotChangeHasBeforeIf
{
    private $name;
    private $age;
    private $gender;

    public function __construct(bool $initName = true)
    {
        $this->age = 20;

        if (!$initName) {
            $this->gender = 'M';

            return;
        }

        $this->name = 'John';
    }
}
?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector\Fixture;

final class DonotChangeHasBeforeIf
{
    private $name;
    private $age = 20;
    private $gender;

    public function __construct(bool $initName = true)
    {
        if (!$initName) {
            $this->gender = 'M';

            return;
        }

        $this->name = 'John';
    }
}
?>
