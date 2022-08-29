<?php

declare(strict_types=1);

namespace Rector\PhpDocParser\ValueObject\NodeBuilder;

use PhpParser\Builder\Namespace_;

/**
 * @api
 * Fixed duplicated naming in php-parser and prevents confusion
 */
final class NamespaceBuilder extends Namespace_
{
}
