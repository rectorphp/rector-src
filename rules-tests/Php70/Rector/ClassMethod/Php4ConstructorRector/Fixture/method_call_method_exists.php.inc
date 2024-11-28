<?php

class SomeParentA2
{
	public function SomeParentA2()
    {
    }
}

final class SomeChildB2 extends SomeParentA2
{
    public function SomeChildB2() {
        $this->SomeParentA2();
    }

    public function SomeParentA2()
    {
    }
}

?>
-----
<?php

class SomeParentA2
{
	public function __construct()
    {
    }
}

final class SomeChildB2 extends SomeParentA2
{
    public function __construct() {
        $this->SomeParentA2();
    }

    public function SomeParentA2()
    {
    }
}

?>
