<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorReadonlyClassRector\Fixture;

final class SkipLiteralIncompatibleType
{
    /**
     * @var string
     */
    public $due_date;

    public function __construct()
    {
        $this->due_date = true;
    }
}
