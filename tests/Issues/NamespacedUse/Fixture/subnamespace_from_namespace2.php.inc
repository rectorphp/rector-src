<?php

namespace App\Bar;

use App;

final class SubNamepaceFromNamespace2
{
    public function run(Bar\Baz $baz)
    {
    }
}

?>
-----
<?php

namespace App\Bar;

final class SubNamepaceFromNamespace2
{
    public function run(Bar\Baz $baz)
    {
    }
}

?>