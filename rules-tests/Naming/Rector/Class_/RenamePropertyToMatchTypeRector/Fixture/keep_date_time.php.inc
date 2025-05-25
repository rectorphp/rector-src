<?php

namespace Rector\Tests\Naming\Rector\Class_\RenamePropertyToMatchTypeRector\Fixture;

final class KeepDateTime
{
    private ?DateTime $timestamp = null;

    public function setValue(
        \DateTime $timestamp,
    ) {
        $this->timestamp = $timestamp;
    }
}
