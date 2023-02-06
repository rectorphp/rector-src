<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\Source\NativelyTypedDataObject;

final class NativelyTypedPropertyFromDataObject
{
    private $property;

    public function __construct(NativelyTypedDataObject $dataObject)
    {
        $this->property = $dataObject->nativelyTypedProperty;
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\Source\NativelyTypedDataObject;

final class NativelyTypedPropertyFromDataObject
{
    private string $property;

    public function __construct(NativelyTypedDataObject $dataObject)
    {
        $this->property = $dataObject->nativelyTypedProperty;
    }
}

?>
