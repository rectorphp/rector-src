<?php

namespace Rector\Tests\CodeQuality\Rector\If_\SimplifyIfReturnBoolRector\Fixture;

class EqualNotEqual
{
    public function notTrue($value)
    {
        if ($value != '0') {
            return true;
        }

        return false;
    }

    public function notFalse($value)
    {
        if ($value != '0') {
            return false;
        }

        return true;
    }

    public function eqTrue($value)
    {
        if ($value == '0') {
            return true;
        }

        return false;
    }

    public function eqFalse($value)
    {
        if ($value == '0') {
            return false;
        }

        return true;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\If_\SimplifyIfReturnBoolRector\Fixture;

class EqualNotEqual
{
    public function notTrue($value)
    {
        return $value != '0';
    }

    public function notFalse($value)
    {
        return $value == '0';
    }

    public function eqTrue($value)
    {
        return $value == '0';
    }

    public function eqFalse($value)
    {
        return $value != '0';
    }
}

?>
