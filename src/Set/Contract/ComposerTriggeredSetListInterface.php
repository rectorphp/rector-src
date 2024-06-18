<?php

declare(strict_types=1);

namespace Rector\Set\Contract;

interface ComposerTriggeredSetListInterface extends SetListInterface
{
    /**
     * Key used in @see \Rector\Configuration\RectorConfigBuilder::withPreparedSets() method
     */
    public static function getName(): string;

    public static function getPackageName(): string;

    public static function getPackageVersion(): string;
}
