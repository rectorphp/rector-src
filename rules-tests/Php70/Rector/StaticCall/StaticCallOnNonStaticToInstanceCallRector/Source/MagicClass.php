<?php
declare(strict_types=1);

namespace Rector\Tests\Php70\Rector\StaticCall\StaticCallOnNonStaticToInstanceCallRector\Source;

class MagicClass
{
    public function __construct() {}

    public static function __set_state($state): static { return new static(); }

    public function __destruct() {}

    public function __call($name, $value): void {}

    public function __get($value): void {}

    public function __set($key, $value): void {}

    public function __isset($key): bool { return false; }

    public function __unset($key): void {}

    public function __sleep(): array{ return []; }

    public function __wakeup(): void {}

    public function __serialize(): array { return [];}

    public function __unserialize($content): void {}

    public function __toString(): string { return ''; }

    public function __invoke(): void {}

    public function __clone() {}

    public function __debugInfo(): array { return []; }
}
