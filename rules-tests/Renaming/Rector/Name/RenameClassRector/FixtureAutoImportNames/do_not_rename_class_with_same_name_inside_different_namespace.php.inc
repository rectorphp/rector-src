<?php

namespace Foo;

interface Foo extends FooBar
{
    /**
     * @param \DateTime $foo
     *
     * Storage here is Foo\Storage. Not \Storage.
     * @return Storage
     */
    public function bar($foo): array;
}

?>
-----
<?php

namespace Foo;

use DateTime;
interface Foo extends FooBar
{
    /**
     * @param DateTime $foo
     *
     * Storage here is Foo\Storage. Not \Storage.
     * @return Storage
     */
    public function bar($foo): array;
}

?>
