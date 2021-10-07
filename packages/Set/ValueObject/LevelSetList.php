<?php

declare(strict_types=1);

namespace Rector\Set\ValueObject;

use Rector\Set\Contract\SetListInterface;

final class LevelSetList implements SetListInterface
{
    /**
     * @var string
     */
    public const UP_TO_PHP80 = __DIR__ . '/../../../config/set/defluent.php';
}
