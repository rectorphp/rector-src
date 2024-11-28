<?php

class B
{
	public function B()
    {
    }
}

final class A extends B
{
    public function A() {
        $this->B();
    }
}

?>
-----
<?php

class B
{
	public function __construct()
    {
    }
}

final class A extends B
{
    public function __construct() {
        \B::__construct();
    }
}

?>
