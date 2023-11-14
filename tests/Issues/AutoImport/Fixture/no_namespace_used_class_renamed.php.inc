<?php

function skipNoNamespaceUsedClassRenamed(
    // fqcn
    \Some\Exception $e
)
{
    // from root
    $obj = new Exception();
}

?>
-----
<?php

function skipNoNamespaceUsedClassRenamed(
    // fqcn
    \Some\Target\Exception $e
)
{
    // from root
    $obj = new Exception();
}

?>
