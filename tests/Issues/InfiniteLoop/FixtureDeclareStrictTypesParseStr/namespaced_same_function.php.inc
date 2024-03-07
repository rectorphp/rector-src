<?php

namespace Foo;

function foo()
{
    return 'foo';
}

echo foo();

?>
-----
<?php

declare(strict_types=1);

namespace Foo;

function foo()
{
    return 'foo';
}

echo foo();
