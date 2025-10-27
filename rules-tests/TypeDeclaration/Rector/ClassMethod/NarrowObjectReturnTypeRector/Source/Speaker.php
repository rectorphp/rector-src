<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Source;

class Speaker
{
    public function createTalk(): ConferenceTalk
    {
        return new ConferenceTalk();
    }
}