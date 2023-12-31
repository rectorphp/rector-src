<?php

namespace Rector\Tests\Issues\GetCalledClassToSelfAndStatic\Fixture;

final class OnFinalClass
{
    public function run()
    {
        var_dump( get_called_class() );
    }
}

?>
-----
<?php

namespace Rector\Tests\Issues\GetCalledClassToSelfAndStatic\Fixture;

final class OnFinalClass
{
    public function run()
    {
        var_dump( self::class );
    }
}

?>
