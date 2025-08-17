<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddReturnDocblockForScalarArrayFromAssignsRector\Fixture;

function getSomeItems()
{
    $items = [];
    $items[] = 'hey';
    $items[] = 'hello';
    return $items;
}

class SomeClass
{
    public function getStringItems()
    {
        $items = [];
        $items[] = 'first';
        $items[] = 'second';
        return $items;
    }

    public function getIntItems()
    {
        $numbers = [];
        $numbers[] = 1;
        $numbers[] = 2;
        $numbers[] = 3;
        return $numbers;
    }

    public function getFloatItems()
    {
        $floats = [];
        $floats[] = 1.5;
        $floats[] = 2.5;
        return $floats;
    }
}

function withNativeArrayType(): array
{
    $items = [];
    $items[] = 'native';
    $items[] = 'array';
    return $items;
}

class WithNativeArrayReturn
{
    public function getNumbers(): array
    {
        $numbers = [];
        $numbers[] = 42;
        $numbers[] = 100;
        return $numbers;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddReturnDocblockForScalarArrayFromAssignsRector\Fixture;

/**
 * @return string[]
 */
function getSomeItems()
{
    $items = [];
    $items[] = 'hey';
    $items[] = 'hello';
    return $items;
}

class SomeClass
{
    /**
     * @return string[]
     */
    public function getStringItems()
    {
        $items = [];
        $items[] = 'first';
        $items[] = 'second';
        return $items;
    }

    /**
     * @return int[]
     */
    public function getIntItems()
    {
        $numbers = [];
        $numbers[] = 1;
        $numbers[] = 2;
        $numbers[] = 3;
        return $numbers;
    }

    /**
     * @return float[]
     */
    public function getFloatItems()
    {
        $floats = [];
        $floats[] = 1.5;
        $floats[] = 2.5;
        return $floats;
    }
}

/**
 * @return string[]
 */
function withNativeArrayType(): array
{
    $items = [];
    $items[] = 'native';
    $items[] = 'array';
    return $items;
}

class WithNativeArrayReturn
{
    /**
     * @return int[]
     */
    public function getNumbers(): array
    {
        $numbers = [];
        $numbers[] = 42;
        $numbers[] = 100;
        return $numbers;
    }
}

?>
