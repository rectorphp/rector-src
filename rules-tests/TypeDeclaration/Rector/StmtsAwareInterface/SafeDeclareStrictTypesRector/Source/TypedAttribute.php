<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\StmtsAwareInterface\SafeDeclareStrictTypesRector\Source;

use Attribute;

#[Attribute]
final class TypedAttribute
{
    public function __construct(
        public int $value
    ) {
    }
}
