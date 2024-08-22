<?php

declare(strict_types=1);

namespace Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint\Source;

/**
 * @Annotation
 * @Target({"PROPERTY", "CLASS"})
 */
final class ApiFilter
{
    public function __construct($options = [])
    {
    }
}
