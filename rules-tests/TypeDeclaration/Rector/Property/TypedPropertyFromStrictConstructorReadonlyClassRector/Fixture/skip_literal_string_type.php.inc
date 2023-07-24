<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorReadonlyClassRector\Fixture;

final class SkipLiteralStringType
{
    /**
     * @var string
     */
    public $due_date;

    public function __construct()
    {
        $this->due_date = '';
    }
}
