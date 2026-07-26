<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\FixturePhp80;

final class PathInfoReturn
{
    /**
     * Extracts the extension of the given filename.
     *
     * @param string $filename Filename
     *
     * @return string Extension of $filename
     *
     * @psalm-assert-if-true =non-empty-string $filename
     */
    public static function extension($filename)
    {
        return pathinfo($filename, PATHINFO_EXTENSION);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\FixturePhp80;

final class PathInfoReturn
{
    /**
     * Extracts the extension of the given filename.
     *
     * @param string $filename Filename
     *
     * @return string Extension of $filename
     *
     * @psalm-assert-if-true =non-empty-string $filename
     */
    public static function extension($filename): string|array
    {
        return pathinfo($filename, PATHINFO_EXTENSION);
    }
}

?>
