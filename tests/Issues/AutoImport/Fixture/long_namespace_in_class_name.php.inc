<?php

namespace App\Foo\Bar\Baz\Bat;

class LongNamespaceInClassName
{
    public \App\Foo\Bar\Baz\Bat\LongNamespaceInClassName $obj;
}

?>
-----
<?php

namespace App\Foo\Bar\Baz\Bat;

class LongNamespaceInClassName
{
    public LongNamespaceInClassName $obj;
}

?>
