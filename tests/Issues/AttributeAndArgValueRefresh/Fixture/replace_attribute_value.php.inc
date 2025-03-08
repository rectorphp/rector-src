<?php

namespace Rector\Tests\Issues\AttributeAndArgValueRefresh;

final class ReplaceAttributeValue
{
    #[\SomeAttribute('value')]
    public function run()
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Issues\AttributeAndArgValueRefresh;

final class ReplaceAttributeValue
{
    #[\SomeAttribute(['value'])]
    public function run()
    {
    }
}

?>
