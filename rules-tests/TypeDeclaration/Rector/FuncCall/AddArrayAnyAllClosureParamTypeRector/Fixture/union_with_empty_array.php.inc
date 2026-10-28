<?php

namespace Rector\Tests\TypeDeclaration\Rector\FuncCall\AddArrayAnyAllClosureParamTypeRector\Fixture;

function unionWithEmptyArray(\SimpleXMLElement $xml)
{
    $elements = $xml->xpath('//item');

    return array_any($elements ?: [], fn ($element): bool => (string) $element === 'a');
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\FuncCall\AddArrayAnyAllClosureParamTypeRector\Fixture;

function unionWithEmptyArray(\SimpleXMLElement $xml)
{
    $elements = $xml->xpath('//item');

    return array_any($elements ?: [], fn (\SimpleXMLElement $element): bool => (string) $element === 'a');
}

?>
