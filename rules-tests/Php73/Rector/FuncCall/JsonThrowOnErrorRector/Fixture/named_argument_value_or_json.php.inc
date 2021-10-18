<?php

namespace Rector\Tests\Php73\Rector\FuncCall\JsonThrowOnErrorRector\Fixture;

function NamedArgumentValueOrJson($str, array $data){
    json_decode(json: $str);
    json_encode(value: $data);
}

?>
-----
<?php

namespace Rector\Tests\Php73\Rector\FuncCall\JsonThrowOnErrorRector\Fixture;

function NamedArgumentValueOrJson($str, array $data){
    json_decode(json: $str, null, 512, JSON_THROW_ON_ERROR);
    json_encode(value: $data, JSON_THROW_ON_ERROR);
}

?>