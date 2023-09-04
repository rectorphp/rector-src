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
        $compare = SomeEnum::USED_TO_BE_CONST()->equals($this->getEnum());
        $compare = SomeEnum::USED_TO_BE_CONST()->equals(self::getStaticEnum());
        $compare = SomeEnum::create()->equals(self::getStaticEnum());

        $compare = $var->equals(SomeEnum::USED_TO_BE_CONST());
        $compare = $var->equals($var);
        $compare = $var->equals($this->prop);

        $compare = $this->prop->equals(SomeEnum::USED_TO_BE_CONST());
        $compare = $this->prop->equals($var);
        $compare = $this->prop->equals($this->prop);

        $compare = $this->getEnum()->equals(SomeEnum::USED_TO_BE_CONST());
        $compare = $this->getEnum()->equals($var);
        $compare = $this->getEnum()->equals($this->prop);

        $compare = self::getStaticEnum()->equals(SomeEnum::USED_TO_BE_CONST());
        $compare = self::getStaticEnum()->equals($var);
        $compare = self::getStaticEnum()->equals($this->prop);

        $compare = $var->equals($this->getEnum());
        $compare = $this->prop->equals($this->getEnum());

        $compare = $var->equals(self::getStaticEnum());
        $compare = $this->prop->equals(self::getStaticEnum());
    }

    public function getEnum(): SomeEnum
    {
        return SomeEnum::USED_TO_BE_CONST();
    }

    public static function getStaticEnum(): SomeEnum
    {
        return SomeEnum::USED_TO_BE_CONST();
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
        $compare = \Rector\Tests\Php81\Rector\MethodCall\MyCLabsMethodCallToEnumConstRector\Source\SomeEnum::USED_TO_BE_CONST === $this->getEnum();
        $compare = \Rector\Tests\Php81\Rector\MethodCall\MyCLabsMethodCallToEnumConstRector\Source\SomeEnum::USED_TO_BE_CONST === self::getStaticEnum();
        $compare = SomeEnum::create() === self::getStaticEnum();

        $compare = $var === \Rector\Tests\Php81\Rector\MethodCall\MyCLabsMethodCallToEnumConstRector\Source\SomeEnum::USED_TO_BE_CONST;
        $compare = $var === $var;
        $compare = $var === $this->prop;

        $compare = $this->prop === \Rector\Tests\Php81\Rector\MethodCall\MyCLabsMethodCallToEnumConstRector\Source\SomeEnum::USED_TO_BE_CONST;
        $compare = $this->prop === $var;
        $compare = $this->prop === $this->prop;

        $compare = $this->getEnum() === \Rector\Tests\Php81\Rector\MethodCall\MyCLabsMethodCallToEnumConstRector\Source\SomeEnum::USED_TO_BE_CONST;
        $compare = $this->getEnum() === $var;
        $compare = $this->getEnum() === $this->prop;

        $compare = self::getStaticEnum() === \Rector\Tests\Php81\Rector\MethodCall\MyCLabsMethodCallToEnumConstRector\Source\SomeEnum::USED_TO_BE_CONST;
        $compare = self::getStaticEnum() === $var;
        $compare = self::getStaticEnum() === $this->prop;

        $compare = $var === $this->getEnum();
        $compare = $this->prop === $this->getEnum();

        $compare = $var === self::getStaticEnum();
        $compare = $this->prop === self::getStaticEnum();
    }

    public function getEnum(): SomeEnum
    {
        return \Rector\Tests\Php81\Rector\MethodCall\MyCLabsMethodCallToEnumConstRector\Source\SomeEnum::USED_TO_BE_CONST;
    }

    public static function getStaticEnum(): SomeEnum
    {
        return \Rector\Tests\Php81\Rector\MethodCall\MyCLabsMethodCallToEnumConstRector\Source\SomeEnum::USED_TO_BE_CONST;
    }
}

?>
