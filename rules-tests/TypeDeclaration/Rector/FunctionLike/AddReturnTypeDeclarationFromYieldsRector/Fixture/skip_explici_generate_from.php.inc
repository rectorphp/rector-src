<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddReturnTypeDeclarationFromYieldsRector\Fixture;

final class SkipExplicitGenerateFrom
{
    /**
     * @return Generator<string, string>
     */
    public function run(string $part): \Generator
    {
        $result = ['a'];
        parse_str($part, $result);

        yield from $result;
    }
}
