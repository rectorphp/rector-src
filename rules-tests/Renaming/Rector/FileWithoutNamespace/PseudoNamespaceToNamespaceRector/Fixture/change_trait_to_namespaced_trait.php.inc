<?php

trait ChangeMe_BarTrait
{
    public function bar(): bool
    {
        return true;
    }
}
-----
<?php

namespace ChangeMe;

trait BarTrait
{
    public function bar(): bool
    {
        return true;
    }
}
