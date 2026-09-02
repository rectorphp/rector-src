<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\AddVarArrayDocblockFromDimFetchAssignRector\Fixture;

final class SkipKeyedAssign
{
    private array $response = [];

    public function run(): void
    {
        $this->response[] = [
            'name' => 'John',
        ];

        $this->response['error'] = 'some error';
        $this->response['done']  = true;
    }
}
