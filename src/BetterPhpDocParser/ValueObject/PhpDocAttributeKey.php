<?php

declare(strict_types=1);

namespace Rector\BetterPhpDocParser\ValueObject;

use Rector\PhpDocParser\PhpDocParser\ValueObject\PhpDocAttributeKey as NativePhpDocAttributeKey;

final class PhpDocAttributeKey
{
    public const string START_AND_END = 'start_and_end';

    /**
     * Fully qualified name of identifier type class
     */
    public const string RESOLVED_CLASS = 'resolved_class';

    public const string PARENT = NativePhpDocAttributeKey::PARENT;

    public const string LAST_PHP_DOC_TOKEN_POSITION = 'last_token_position';

    public const string ORIG_NODE = NativePhpDocAttributeKey::ORIG_NODE;
}
