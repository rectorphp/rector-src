<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddParamArrayDocblockFromDimFetchAccessRector\Source;

interface EnforcingSomeContractInterface
{
    /**
     * @param mixed $data
     */
    public function process(array $data): void;
}
