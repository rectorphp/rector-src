<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

if (class_exists('Symfony\Component\Validator\Constraints\Callback')) {
    return;
}

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class Callback
{
    /**
     * @var string|callable
     */
    public $callback;

    public function __construct(array|string|callable|null $callback = null, ?array $groups = null, mixed $payload = null, array $options = [])
    {
    }
}
