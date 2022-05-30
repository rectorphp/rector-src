<?php

namespace Rector\Tests\BetterPhpDocParser\PhpDocParser\ClassAnnotationMatcher\Fixture;

use Rector\Tests\BetterPhpDocParser\PhpDocParser\ClassAnnotationMatcher\Source\KnownClass;
use My\Namespace\OtherUnknownClass;

class MyClass
{
    /** @var KnownClass */
    private $known;
    /** @var \SplHeap */
    private $knownGlobalClass;
    /** @var UnknownClass */
    private $unknownClass;
    /** @var \My\Namespace\UnknownClass */
    private $unknownNamespacedClass;
    /** @var OtherUnknownClass */
    private $unknownUsedClass;
    /** @var \UnknownGlobalClass */
    private $unknownGlobalClass;
}
