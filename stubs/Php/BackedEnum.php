<?php

declare(strict_types=1);

if (interface_exists('BackedEnum')) {
    return;
}

if (! interface_exists('UnitEnum')) {
    // avoid overlapped use
    require_once __DIR__ . '/UnitEnum.php';
}

interface BackedEnum extends UnitEnum {
    public static function from(int|string $value): static;
    public static function tryFrom(int|string $value): ?static;
}
