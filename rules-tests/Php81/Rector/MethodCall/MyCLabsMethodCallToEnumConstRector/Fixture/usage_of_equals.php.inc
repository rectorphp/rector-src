<?php

namespace Rector\Tests\Php81\Rector\MethodCall\MyCLabsMethodCallToEnumConstRector\Fixture;

use Rector\Tests\Php81\Rector\MethodCall\MyCLabsMethodCallToEnumConstRector\Source\SomeEnum;

final class UsageOfEquals
{
    private SomeEnum $prop;

    public function run($value)
    {
        $this->prop = $var = SomeEnum::USED_TO_BE_CONST();

        $compare = SomeEnum::USED_TO_BE_CONST()->equals(SomeEnum::USED_TO_BE_CONST());
        $compare = SomeEnum::USED_TO_BE_CONST()->equals($var);
        $compare = SomeEnum::USED_TO_BE_CONST()->equals($this->prop);

        $compare = $var->equals(SomeEnum::USED_TO_BE_CONST());
        $compare = $var->equals($var);
        $compare = $var->equals($this->prop);

        $compare = $this->prop->equals(SomeEnum::USED_TO_BE_CONST());
        $compare = $this->prop->equals($var);
        $compare = $this->prop->equals($this->prop);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php81\Rector\MethodCall\MyCLabsMethodCallToEnumConstRector\Fixture;

use Rector\Tests\Php81\Rector\MethodCall\MyCLabsMethodCallToEnumConstRector\Source\SomeEnum;

final class UsageOfEquals
{
    private SomeEnum $prop;

    public function run($value)
    {
        $this->prop = $var = \Rector\Tests\Php81\Rector\MethodCall\MyCLabsMethodCallToEnumConstRector\Source\SomeEnum::USED_TO_BE_CONST;

        $compare = \Rector\Tests\Php81\Rector\MethodCall\MyCLabsMethodCallToEnumConstRector\Source\SomeEnum::USED_TO_BE_CONST === \Rector\Tests\Php81\Rector\MethodCall\MyCLabsMethodCallToEnumConstRector\Source\SomeEnum::USED_TO_BE_CONST;
        $compare = \Rector\Tests\Php81\Rector\MethodCall\MyCLabsMethodCallToEnumConstRector\Source\SomeEnum::USED_TO_BE_CONST === $var;
        $compare = \Rector\Tests\Php81\Rector\MethodCall\MyCLabsMethodCallToEnumConstRector\Source\SomeEnum::USED_TO_BE_CONST === $this->prop;

        $compare = $var === \Rector\Tests\Php81\Rector\MethodCall\MyCLabsMethodCallToEnumConstRector\Source\SomeEnum::USED_TO_BE_CONST;
        $compare = $var === $var;
        $compare = $var === $this->prop;

        $compare = $this->prop === \Rector\Tests\Php81\Rector\MethodCall\MyCLabsMethodCallToEnumConstRector\Source\SomeEnum::USED_TO_BE_CONST;
        $compare = $this->prop === $var;
        $compare = $this->prop === $this->prop;
    }
}

?>
