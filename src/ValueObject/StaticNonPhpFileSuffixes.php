<?php

declare(strict_types=1);

namespace Rector\Core\ValueObject;

final class StaticNonPhpFileSuffixes
{
    /**
     * @var string[]
     */
    public const SUFFIXES = ['neon', 'yaml', 'xml', 'yml', 'twig', 'latte', 'blade.php', 'tpl'];

}
