<?php

namespace Some;

class Foo extends FooBar
{
    /**
     * @return Storage
     */
    public function bar(){}
}

?>
-----
<?php

namespace Some;

class Foo extends FooBar
{
    /**
     * @return \Illuminate\Support\Facades\Storage
     */
    public function bar(){}
}

?>
