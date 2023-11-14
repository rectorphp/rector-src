<?php

function NoNamespaceUsedClassRenamed(
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

function NoNamespaceUsedClassRenamed(
    // fqcn
    \Some\Target\Exception $e
)
{
    // from root
    $obj = new Exception();
}

?>
