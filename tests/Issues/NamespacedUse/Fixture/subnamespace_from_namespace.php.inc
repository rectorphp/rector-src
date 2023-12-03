<?php

namespace App\Bar;

use App;

final class SubNamepaceFromNamespace
{
    public function run(Baz $baz)
    {
    }
}

?>
-----
<?php

namespace App\Bar;

final class SubNamepaceFromNamespace
{
    public function run(Baz $baz)
    {
    }
}

?>