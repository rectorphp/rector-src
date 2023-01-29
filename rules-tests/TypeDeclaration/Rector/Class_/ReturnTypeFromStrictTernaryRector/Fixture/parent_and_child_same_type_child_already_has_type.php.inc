<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\ReturnTypeFromStrictTernaryRector\Fixture;

/**
 * Both classes need to in single file as the changed part is parent
 */
final class SomeParent
{
    const FIRST = 'hey';
    const SECOND = 'hou';

    public function getValue($number)
    {
        return $number ? self::FIRST : self::SECOND;
    }
}

final class SomeChild extends SomeParent
{
    const FIRST = 'hey';
    const SECOND = 'hou';

    public function getValue($number): bool
    {
        return $number ? self::FIRST : self::SECOND;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\ReturnTypeFromStrictTernaryRector\Fixture;

/**
 * Both classes need to in single file as the changed part is parent
 */
final class SomeParent
{
    const FIRST = 'hey';
    const SECOND = 'hou';

    public function getValue($number)
    {
        return $number ? self::FIRST : self::SECOND;
    }
}

final class SomeChild extends SomeParent
{
    const FIRST = 'hey';
    const SECOND = 'hou';

    public function getValue($number): bool
    {
        return $number ? self::FIRST : self::SECOND;
    }
}

?>
