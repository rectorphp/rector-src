<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\Source\NotNativelyTypedDataObject;

final class SkipNotNativelyTypedPropertyFromDataObject
{
    private $property;

    public function __construct(NotNativelyTypedDataObject $dataObject)
    {
        $this->property = $dataObject->notNativelyTypedProperty;
    }
}
