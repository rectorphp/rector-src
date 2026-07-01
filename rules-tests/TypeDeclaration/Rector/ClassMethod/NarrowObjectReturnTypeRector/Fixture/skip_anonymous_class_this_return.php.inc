<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector\Source\Email;

final class EmailFactory
{
    private function createEmail(): Email
    {
        return new class extends Email {
            private int $id = 0;

            public function getId(): int
            {
                return $this->id;
            }

            public function setId(int $id): Email
            {
                $this->id = $id;

                return $this;
            }
        };
    }
}
