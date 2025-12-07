<?php

namespace Rector\Tests\Privatization\Rector\ClassMethod\PrivatizeFinalClassMethodRector\Fixture;

use PhpParser\PrettyPrinter\Standard;
<<<<<<< HEAD
=======
use Rector\PhpParser\Node\FileNode;
>>>>>>> fb58990e39 (extract LaravelClassName)

final class KeepMagicParentCalledMethod extends Standard
{
    // called from parent by $this->{'p'} method
<<<<<<< HEAD
    protected function pFileNode($fileNode)
=======
    protected function pFileNode(FileNode $fileNode)
>>>>>>> fb58990e39 (extract LaravelClassName)
    {
    }
}
