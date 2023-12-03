<?php

declare(strict_types=1);

namespace Rector\Core\Exception;

use PhpParser\Node\Name;
use RuntimeException;

final class FullyQualifiedNameNotAutoloadedException extends RuntimeException
{
    public function __construct(
        protected Name $name
    ) {
        parent::__construct(sprintf('%s was not autoloaded', $name->toString()));
    }
}
