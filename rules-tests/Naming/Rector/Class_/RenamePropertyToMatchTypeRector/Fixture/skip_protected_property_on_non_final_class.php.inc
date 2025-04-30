<?php

namespace Rector\Tests\Naming\Rector\Class_\RenamePropertyToMatchTypeRector\Fixture;

use Rector\Tests\Naming\Rector\Class_\RenamePropertyToMatchTypeRector\Source\GitWrapper;

class SkipProtectedPropertyOnNonFinalClass
{
    /**
     * @var \Rector\Tests\Naming\Rector\Class_\RenamePropertyToMatchTypeRector\Source\GitWrapper
     */
    protected $wrapper;

    public function __construct(GitWrapper $wrapper)
    {
        $this->wrapper = $wrapper;
    }
}
