<?php

declare(strict_types=1);

namespace Rector\PhpDocParser\PhpDocParser\ValueObject;

use PHPStan\PhpDocParser\Ast\Attribute;

final class PhpDocAttributeKey
{
    public const string PARENT = 'parent';

    public const string ORIG_NODE = Attribute::ORIGINAL_NODE;
}
