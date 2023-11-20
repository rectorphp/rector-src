<?php

function skipNoNamespaceUsedClass(
    // fqcn
    \PHPUnit\Framework\MockObject\Stub\Exception $e
)
{
    // from root
    $obj = new Exception();
}
