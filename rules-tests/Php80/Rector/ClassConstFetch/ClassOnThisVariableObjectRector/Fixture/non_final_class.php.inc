<?php

namespace Rector\Tests\Php80\Rector\ClassConstFetch\ClassOnThisVariableObjectRector\Fixture;

class NonFinalClass
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

class NonFinalClass
{
    public function run()
    {
        return static::class;
    }
}

?>
