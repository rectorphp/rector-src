<?php

declare(strict_types=1);

namespace Rector\PostRector\Contract;

/**
 * Services with this interface will be run after the whole recto run is finished,
 * this is useful e.g. to dump collected data to a single file.
 */
interface AfterRectorRunnerInterface
{
    public function run(): void;
}
