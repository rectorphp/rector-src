<?php

namespace Foo2;

class AutoImportConflictNameAllFqcnSameNamespace extends FooBar
{
    /**
     * @param \DateTime $foo
     *
     * @return \Foo2\Storage|\Storage
     */
    public function bar($foo){}
}

?>
-----
<?php

namespace Foo2;

use DateTime;
class AutoImportConflictNameAllFqcnSameNamespace extends FooBar
{
    /**
     * @param DateTime $foo
     *
     * @return Storage|\Illuminate\Support\Facades\Storage
     */
    public function bar($foo){}
}

?>
