<?php

declare(strict_types=1);

namespace E2e\TypedPropertyNamespacedString;

final class SomeClassWithArrayPropertyValue
{
    private $config = [
        'templates' => [
            'foo' => [
                'single' => 'E2e\TypedPropertyNamespacedString\View\single',
            ],
        ],
    ];
}
