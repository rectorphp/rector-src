<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddParamTypeFromPropertyTypeRector\Fixture;

final class SkipNotNativelyTypedProperty
{
    /**
     * @var int
     */
    private $user;

    public function run($user)
    {
        $this->user = $user;
    }
}
