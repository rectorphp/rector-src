<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector\Source;

final class SomeResponse implements SomeResponseInterface
{
    public function setBody(string $body)
    {
        $this->body = $body;
    }
}
