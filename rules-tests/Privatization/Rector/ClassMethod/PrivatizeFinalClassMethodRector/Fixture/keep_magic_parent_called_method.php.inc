<?php

namespace Rector\Tests\Privatization\Rector\ClassMethod\PrivatizeFinalClassMethodRector\Fixture;

use PhpParser\PrettyPrinter\Standard;
use Rector\PhpParser\Node\FileNode;

final class KeepMagicParentCalledMethod extends Standard
{
    // called from parent by $this->{'p'} method
    protected function pFileNode(FileNode $fileNode)
    {
    }
}
