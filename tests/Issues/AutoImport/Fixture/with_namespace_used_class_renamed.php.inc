<?php

namespace App;

function WithNamespaceUsedClassRenamed(
    // fqcn
    \Some\Exception $e
)
{
    // from root namespace App
    $obj = new Exception();
}

?>
-----
<?php

namespace App;

function WithNamespaceUsedClassRenamed(
    // fqcn
    \Some\Target\Exception $e
)
{
    // from root namespace App
    $obj = new Exception();
}

?>
