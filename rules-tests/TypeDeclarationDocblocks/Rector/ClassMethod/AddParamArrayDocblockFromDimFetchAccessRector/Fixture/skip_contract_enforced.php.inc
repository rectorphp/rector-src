<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddParamArrayDocblockFromDimFetchAccessRector\Fixture;

use Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddParamArrayDocblockFromDimFetchAccessRector\Source\EnforcingSomeContractInterface;

final class SkipContractEnforced implements EnforcingSomeContractInterface
{
    public function process(array $data): void
    {
    }
}
