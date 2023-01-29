<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\ReturnTypeFromStrictTernaryRector\Fixture;

final class LocalConstants
{
    const FIRST = 'hey';
    const SECOND = 'hou';

    public function getValue($number)
    {
        return $number ? self::FIRST : self::SECOND;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\ReturnTypeFromStrictTernaryRector\Fixture;

final class LocalConstants
{
    const FIRST = 'hey';
    const SECOND = 'hou';

    public function getValue($number): string
    {
        return $number ? self::FIRST : self::SECOND;
    }
}

?>
