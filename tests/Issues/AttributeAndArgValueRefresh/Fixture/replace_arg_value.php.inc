<?php

namespace Rector\Tests\Issues\AttributeAndArgValueRefresh;

final class ReplaceArgValue
{
    public function run()
    {
        execute('');
    }
}

?>
-----
<?php

namespace Rector\Tests\Issues\AttributeAndArgValueRefresh;

final class ReplaceArgValue
{
    public function run()
    {
        execute(['value']);
    }
}

?>
