<?php

class SomeParentA
{
	public function SomeParentA()
    {
    }
}

final class SomeChildB extends SomeParentA
{
    public function SomeChildB() {
        $this->SomeParentA();
    }
}

?>
-----
<?php

class SomeParentA
{
	public function __construct()
    {
    }
}

final class SomeChildB extends SomeParentA
{
    public function __construct() {
        \SomeParentA::__construct();
    }
}

?>
