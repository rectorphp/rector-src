<?php

namespace Foo3;

class Foo3 extends FooBar
{
    /**
     * @param \DateTime $foo
     *
     * @return Storage|\Storage
     */
    public function bar($foo){}
}

?>
-----
<?php

namespace Foo3;

use DateTime;
class Foo3 extends FooBar
{
    /**
     * @param DateTime $foo
     *
     * @return Storage|\Illuminate\Support\Facades\Storage
     */
    public function bar($foo){}
}

?>
