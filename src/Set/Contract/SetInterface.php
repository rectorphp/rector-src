<?php

declare(strict_types=1);

namespace Rector\Set\Contract;

/**
 * @api used by getrector.com website to build the set tree
 */
interface SetInterface
{
    public function getGroupName(): string;

    public function getName(): string;

    public function getSetFilePath(): string;
}
