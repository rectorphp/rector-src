<?php

namespace Rector\Tests\BetterPhpDocParser\PhpDocParser\ClassAnnotationMatcher\Fixture;

use Rector\Tests\BetterPhpDocParser\PhpDocParser\ClassAnnotationMatcher\Source\KnownClass;
use Rector\Tests\BetterPhpDocParser\PhpDocParser\ClassAnnotationMatcher\Source\KnownClass as AliasedKnownClass;
use My\Namespace\OtherUnknownClass;
use My\Namespace\OtherUnknownClass as AliasedUnknownClass;
use SplHeap as AliasedSplHeap;

class MyClass
{
    /** @var KnownClass */
    private $knownClass;
    /** @var \Rector\Tests\BetterPhpDocParser\PhpDocParser\ClassAnnotationMatcher\Source\KnownClass */
    private $knownInlinedClass;
    /** @var SiblingClass */
    private $knownSiblingClass;
    /** @var AliasedKnownClass */
    private $knownAliasedClass;
    /** @var \SplHeap */
    private $knownGlobalClass;
    /** @var AliasedSplHeap */
    private $knownAliasedGlobalClass;
    /** @var UnknownClass */
    private $unknownClass;
    /** @var \My\Namespace\UnknownClass */
    private $unknownNamespacedClass;
    /** @var OtherUnknownClass */
    private $unknownUsedClass;
    /** @var AliasedUnknownClass */
    private $unknownAliasedUsedClass;
    /** @var \UnknownGlobalClass */
    private $unknownGlobalClass;
}
