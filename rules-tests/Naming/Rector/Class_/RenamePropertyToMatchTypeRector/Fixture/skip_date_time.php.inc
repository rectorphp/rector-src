<?php

namespace Rector\Tests\Naming\Rector\Class_\RenamePropertyToMatchTypeRector\Fixture;

final class SkipDateTime
{
    public function __construct(private readonly ?\DateTimeInterface $updatedAt = null)
    {
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }
}
