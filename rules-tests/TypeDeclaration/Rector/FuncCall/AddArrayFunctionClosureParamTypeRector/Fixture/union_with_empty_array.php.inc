<?php

namespace Rector\Tests\TypeDeclaration\Rector\FuncCall\AddArrayFunctionClosureParamTypeRector\Fixture;

final class UnionWithEmptyArray
{
    public function run(\SimpleXMLElement $xml)
    {
        $elements = $xml->xpath('//item');

        return array_filter($elements ?: [], fn ($element) => (string) $element === 'a');
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\FuncCall\AddArrayFunctionClosureParamTypeRector\Fixture;

final class UnionWithEmptyArray
{
    public function run(\SimpleXMLElement $xml)
    {
        $elements = $xml->xpath('//item');

        return array_filter($elements ?: [], fn (\SimpleXMLElement $element) => (string) $element === 'a');
    }
}

?>
