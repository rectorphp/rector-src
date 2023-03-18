<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\Source;

final class SomeNode
{
    public ?SomeNode $next;
    public string $value;
}