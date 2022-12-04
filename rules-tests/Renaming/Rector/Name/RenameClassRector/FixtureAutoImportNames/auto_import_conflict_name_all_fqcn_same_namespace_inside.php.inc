<?php

namespace Foo2;

class AutoImportConflictNameAllFqcnSameNamespaceInside extends FooBar
{
    /**
     * @param \DateTime $foo
     *
     * @return \Foo2\Bar\Storage|\Storage
     */
    public function bar($foo){}
}

?>
-----
<?php

namespace Foo2;

use DateTime;
use Foo2\Bar\Storage;
class AutoImportConflictNameAllFqcnSameNamespaceInside extends FooBar
{
    /**
     * @param DateTime $foo
     *
     * @return Storage|\Illuminate\Support\Facades\Storage
     */
    public function bar($foo){}
}

?>
