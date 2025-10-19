<?php

namespace Symfony\Component\Validator\Constraints;

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
