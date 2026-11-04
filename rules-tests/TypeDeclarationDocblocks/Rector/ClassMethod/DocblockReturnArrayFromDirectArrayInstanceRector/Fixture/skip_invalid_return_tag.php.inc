<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Fixture;

/**
 * @return array'
 */
function withInvalidReturnTag(): array
{
    return ['value'];
}
