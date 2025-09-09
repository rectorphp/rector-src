<?php

namespace App\Bar;

class FqcnCurrentSameClass
{
    public \App\Bar\FqcnCurrentSameClass $prop;
}

?>
-----
<?php

namespace App\Bar;

class FqcnCurrentSameClass
{
    public FqcnCurrentSameClass $prop;
}

?>
