<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddArrayReturnDocTypeRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddArrayReturnDocTypeRector\Source\InputInterface;

/**
 * @phpstan-import-type InputSpecification from InputInterface
 */
final class UriInput
{
    /**
     * @return InputSpecification
     */
    public function getInputSpecification(): array
    {
        return [
            'name'    => 'uri',
        ];
    }
}
