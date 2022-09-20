<?php

namespace Rector\Tests\Php70\Rector\StaticCall\StaticCallOnNonStaticToInstanceCallRector\Fixture;

class AllowCustomMagicMethodBase
{
    public function __customMagicMethod()
    {
    }
}

class AllowCustomMagicMethodAdditional
{
    public function run()
    {
        return AllowCustomMagicMethodBase::__customMagicMethod();
    }
}

?>
-----
<?php

namespace Rector\Tests\Php70\Rector\StaticCall\StaticCallOnNonStaticToInstanceCallRector\Fixture;

class AllowCustomMagicMethodBase
{
    public function __customMagicMethod()
    {
    }
}

class AllowCustomMagicMethodAdditional
{
    public function run()
    {
        return (new AllowCustomMagicMethodBase())->__customMagicMethod();
    }
}

?>
