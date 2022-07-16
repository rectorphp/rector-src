<?php

namespace Rector\Tests\Renaming\Rector\Namespace_\RenameNamespaceRector\Fixture;

final class SkipUnderscoreNamespace
{
    public function someClass()
    {
        return new \PHPUnit_Framework_Something;
    }
}
