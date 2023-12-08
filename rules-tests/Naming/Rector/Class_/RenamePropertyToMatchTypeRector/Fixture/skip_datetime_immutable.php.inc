<?php

namespace Rector\Tests\Naming\Rector\Class_\RenamePropertyToMatchTypeRector\Fixture;

final class SkipDateTimeImmutable
{
    public function __construct(
        public \DateTimeImmutable $changed,
    ) {}
}
