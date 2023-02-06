<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\Fixture;

final class SkipNotNativelyTypedPropertyFromDataObject
{
    private $property;

    public function __construct(NotNativelyTypedDataObject $dataObject)
    {
        $this->property = $dataObject->notNativelyTypedProperty;
    }
}
