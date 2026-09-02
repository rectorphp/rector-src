<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\DocblockVarArrayFromPropertyDefaultsRector\Fixture;

class SkipNonPrivateProperty
{
    protected array $configParams = ['enabled' => true, 'name' => 'value'];
}
