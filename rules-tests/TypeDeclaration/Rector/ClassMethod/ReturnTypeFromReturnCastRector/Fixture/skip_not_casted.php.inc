<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnCastRector\Fixture;

final class SkipNotCasted
{
    public function returnNotCasted($data)
    {
        try {
            return $data;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}