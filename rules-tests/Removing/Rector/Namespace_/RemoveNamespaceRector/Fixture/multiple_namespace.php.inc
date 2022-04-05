<?php

namespace App
{
    class MultipleNamespace
    {
    }
}

namespace
{
    echo 'test';
}

?>
-----
<?php

namespace {
    class MultipleNamespace
    {
    }
}

namespace
{
    echo 'test';
}

?>
