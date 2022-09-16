<?php

final class SomeFinalClass
{
    final function noExplicitVisFinal()
    {
    }
}

?>
-----
<?php

final class SomeFinalClass
{
    final public function noExplicitVisFinal()
    {
    }
}

?>
