<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\Isset_\IssetOnPropertyObjectToPropertyExistsRector\Source;

use ArrayAccess;

/**
 * @template TKey of array-key
 *
 * @template-covariant TValue
 *
 * @implements ArrayAccess<TKey, TValue>
 */
class Collection implements ArrayAccess {}