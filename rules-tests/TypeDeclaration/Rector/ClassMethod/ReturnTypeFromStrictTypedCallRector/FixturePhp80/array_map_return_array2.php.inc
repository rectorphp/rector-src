<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\FixturePhp80;

final class ArrayMapReturnArray2
{
    public function run()
    {
        $uri = preg_replace_callback('@(?<=\?page=)[\w/]+@', static function (array $match) {
            return str_replace('%2F', '/', $match[0]);
        }, $request->getRequestUri(), 1);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\FixturePhp80;

final class ArrayMapReturnArray2
{
    public function run()
    {
        $uri = preg_replace_callback('@(?<=\?page=)[\w/]+@', static function (array $match): string|array {
            return str_replace('%2F', '/', $match[0]);
        }, $request->getRequestUri(), 1);
    }
}

?>
