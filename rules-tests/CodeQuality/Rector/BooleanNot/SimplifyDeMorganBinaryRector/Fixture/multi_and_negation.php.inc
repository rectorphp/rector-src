<?php

namespace Rector\Tests\CodeQuality\Rector\BooleanNot\SimplifyDeMorganBinaryRector\Fixture;

class MultiAndNegation
{
    public function run($value)
    {
        return ! ( ! is_null($value) && ! $value);
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\BooleanNot\SimplifyDeMorganBinaryRector\Fixture;

class MultiAndNegation
{
    public function run($value)
    {
        return is_null($value) || $value;
    }
}

?>
