<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedPropertyRector\Fixture;

class ReturnStaticProperty
{
    /**
     * @var Service[]
     */
    private static array $registry = [];

    /**
     * find registered Service.
     *
     * @return Service[]
     */
    public static function getRegisteredServices()
    {
        return self::$registry;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedPropertyRector\Fixture;

class ReturnStaticProperty
{
    /**
     * @var Service[]
     */
    private static array $registry = [];

    /**
     * find registered Service.
     *
     * @return Service[]
     */
    public static function getRegisteredServices(): array
    {
        return self::$registry;
    }
}

?>
