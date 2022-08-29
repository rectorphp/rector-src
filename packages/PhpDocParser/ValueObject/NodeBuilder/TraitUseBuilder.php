<?php

declare(strict_types=1);

namespace Rector\PhpDocParser\ValueObject\NodeBuilder;

use PhpParser\Builder\TraitUse;

/**
 * @api
 * Fixed duplicated naming in php-parser and prevents confusion
 */
final class TraitUseBuilder extends TraitUse
{
}
