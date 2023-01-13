<?php

namespace Rector\Tests\Naming\Rector\Class_\RenamePropertyToMatchTypeRector\Fixture;

final class KeepDateTime
{
    public function __construct(
        private \DateTimeInterface $cratedAt,
    ) {
    }
}
