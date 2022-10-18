<?php

interface ChangeMe_FooInterface
{
    public function bar();
}
-----
<?php

namespace ChangeMe;

interface FooInterface
{
    public function bar();
}
