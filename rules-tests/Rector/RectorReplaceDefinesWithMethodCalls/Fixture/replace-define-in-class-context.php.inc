<?php

namespace Privatization\Rector\RectorReplaceDefinesWithMethodCalls\Fixture;

class A
{
    private $foo = FOO_DEFINE;
}
?>
-----
<?php

namespace Privatization\Rector\RectorReplaceDefinesWithMethodCalls\Fixture;

class A
{
    private $foo = \Tests\App::getDefine('FOO_DEFINE');
}
?>
