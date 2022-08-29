<?php

declare(strict_types=1);

namespace Rector\PhpDocParser\ValueObject\NodeBuilder;

use PhpParser\Builder\Param;

/**
 * @api
 * Fixed duplicated naming in php-parser and prevents confusion
 */
final class ParamBuilder extends Param
{
}
