<?php

namespace Rector\Tests\Php73\Rector\FuncCall\JsonThrowOnErrorRector\Fixture;

function processContentFromParam(array $content = [])
{
    json_encode($content);
}

?>
-----
<?php

namespace Rector\Tests\Php73\Rector\FuncCall\JsonThrowOnErrorRector\Fixture;

function processContentFromParam(array $content = [])
{
    json_encode($content, JSON_THROW_ON_ERROR);
}

?>
