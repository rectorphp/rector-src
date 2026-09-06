<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\DocblockVarArrayFromPropertyDefaultsRector\Fixture;

final class SkipEmptyNestedArray
{
    private array $payload = ['create' => [], 'update' => []];

    public function add(string $key, array $item): void
    {
        $this->payload['create'][$key] = $item;
    }
}
