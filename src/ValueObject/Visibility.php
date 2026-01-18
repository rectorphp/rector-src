<?php

declare(strict_types=1);

namespace Rector\ValueObject;

use PhpParser\Modifiers;

final class Visibility
{
    public const int PUBLIC = Modifiers::PUBLIC;

    public const int PROTECTED = Modifiers::PROTECTED;

    public const int PRIVATE = Modifiers::PRIVATE;

    public const int STATIC = Modifiers::STATIC;

    public const int ABSTRACT = Modifiers::ABSTRACT;

    public const int FINAL = Modifiers::FINAL;

    public const int READONLY = Modifiers::READONLY;
}
