<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\AddArrayReturnDocTypeRector\Fixture;

/**
 * @phpstan-type InputSpecification array{name: non-empty-string}
 */
final class UriInput2
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
