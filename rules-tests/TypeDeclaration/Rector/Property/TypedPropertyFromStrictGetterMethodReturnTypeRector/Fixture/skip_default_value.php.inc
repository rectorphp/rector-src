<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromStrictGetterMethodReturnTypeRector\Fixture;

final class SkipDefaultValue
{
    public $name = false;

    public function getName(): string|null
    {
        return $this->name;
    }
}
