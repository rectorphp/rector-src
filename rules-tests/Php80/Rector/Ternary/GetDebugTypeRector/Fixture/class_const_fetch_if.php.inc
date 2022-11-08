<?php

namespace Rector\Tests\Php80\Rector\Ternary\GetDebugTypeRector\Fixture;

class ClassConstFetchClass
{
    public function run($value)
    {
        return is_object($value) ? $value::class : gettype($value);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\Ternary\GetDebugTypeRector\Fixture;

class ClassConstFetchClass
{
    public function run($value)
    {
        return get_debug_type($value);
    }
}

?>
