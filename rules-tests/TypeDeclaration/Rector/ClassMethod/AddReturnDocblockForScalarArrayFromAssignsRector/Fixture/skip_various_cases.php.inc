<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddReturnDocblockForScalarArrayFromAssignsRector\Fixture;

class SkipAlreadyHasReturnType
{
    /**
     * @return array
     */
    public function getItems()
    {
        $items = [];
        $items[] = 'value';
        return $items;
    }
}

class SkipIterableReturnType
{
    public function getItems(): iterable
    {
        $items = [];
        $items[] = 'value';
        return $items;
    }
}

class SkipMixedTypes
{
    public function getMixedItems()
    {
        $items = [];
        $items[] = 'string';
        $items[] = 123;
        return $items;
    }
}

class SkipNoArrayInit
{
    public function getItems()
    {
        $items[] = 'value';
        return $items;
    }
}

class SkipNoReturnVariable
{
    public function getItems()
    {
        $items = [];
        $items[] = 'value';
        return [];
    }
}

class SkipExplicitKey
{
    public function getItems()
    {
        $items = [];
        $items['key'] = 'value';
        return $items;
    }
}

class SkipMixedTypesInMultipleVariables
{
    public function getItems()
    {
        $strings = [];
        $strings[] = 'string';
        
        $mixed = [];
        $mixed[] = 'string';
        $mixed[] = 123;
        
        if (rand(0, 1)) {
            return $strings;
        }
        
        return $mixed;
    }
}
