<?php

namespace Rector\Tests\Naming\Rector\Class_\RenamePropertyToMatchTypeRector\Fixture;

final class KeepDateTimeInterface
{
    public function __construct(
        private \DateTimeInterface $cratedAt,
    ) {
    }
}
