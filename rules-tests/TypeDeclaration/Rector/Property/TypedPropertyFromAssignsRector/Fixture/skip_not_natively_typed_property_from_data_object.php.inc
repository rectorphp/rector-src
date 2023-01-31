<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\Fixture;

class NotNativelyTypedDataObject
{
    /**
     * @var string
     */
    public $notNativelyTypedProperty;
}

final class SkipNotNativelyTypedPropertyFromDataObject
{
    private $property;

    public function __construct(NotNativelyTypedDataObject $dataObject)
    {
        $this->property = $dataObject->notNativelyTypedProperty;
    }
}
