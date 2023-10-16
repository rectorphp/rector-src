<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector\Fixture;

define("FLOAT_CONSTANT_1", 10.1);

final class SomeClass
{
    const INT_CONSTANT_2 = 10;

    private $number1;
    private $number2;
    private $number3;
    private $number4;
    private $number5;
    private $number6;
    private $number7;
    private $number8;
    private $number9;

    public function __construct()
    {
        $this->number1 = +FLOAT_CONSTANT_1;
        $this->number2 = FLOAT_CONSTANT_1;
        $this->number3 = -FLOAT_CONSTANT_1;
        $this->number4 = +self::INT_CONSTANT_2;
        $this->number5 = self::INT_CONSTANT_2;
        $this->number6 = -self::INT_CONSTANT_2;
        $this->number7 = +static::INT_CONSTANT_2;
        $this->number8 = static::INT_CONSTANT_2;
        $this->number9 = -static::INT_CONSTANT_2;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector\Fixture;

define("FLOAT_CONSTANT_1", 10.1);

final class SomeClass
{
    const INT_CONSTANT_2 = 10;

    private $number1 = +FLOAT_CONSTANT_1;
    private $number2 = FLOAT_CONSTANT_1;
    private $number3 = -FLOAT_CONSTANT_1;
    private $number4 = +self::INT_CONSTANT_2;
    private $number5 = self::INT_CONSTANT_2;
    private $number6 = -self::INT_CONSTANT_2;
    private $number7;
    private $number8;
    private $number9;

    public function __construct()
    {
        $this->number7 = +static::INT_CONSTANT_2;
        $this->number8 = static::INT_CONSTANT_2;
        $this->number9 = -static::INT_CONSTANT_2;
    }
}

?>
