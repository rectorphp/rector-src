<?php

namespace Rector\Tests\Php80\Rector\ClassConstFetch\ClassOnThisVariableObjectRector\Fixture;

final class FinalClass
{
    public function run()
    {
        return $this::class;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\ClassConstFetch\ClassOnThisVariableObjectRector\Fixture;

final class FinalClass
{
    public function run()
    {
        return self::class;
    }
}

?>
