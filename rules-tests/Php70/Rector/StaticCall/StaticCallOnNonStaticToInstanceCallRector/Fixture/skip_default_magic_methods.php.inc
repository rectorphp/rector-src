<?php

namespace Rector\Tests\Php70\Rector\StaticCall\StaticCallOnNonStaticToInstanceCallRector\Fixture;

use Rector\Tests\Php70\Rector\StaticCall\StaticCallOnNonStaticToInstanceCallRector\Source\MagicClass;

class SkipDefaultMagicMethods
{
    public function __construct() { MagicClass::__construct(); }

    public static function __set_state($state): static { return MagicClass::__set_state($state); }

    public function __destruct() { MagicClass::__destruct(); }

    public function __call($name, $value): void { MagicClass::__call($name, $value); }

    public function __get($value): void { MagicClass::__get($value); }

    public function __set($key, $value): void { MagicClass::__set($key, $value); }

    public function __isset($key): bool { return MagicClass::__isset($key); }

    public function __unset($key): void { MagicClass::__unset($key); }

    public function __sleep(): array{ return MagicClass::__sleep(); }

    public function __wakeup(): void{ MagicClass::__wakeup(); }

    public function __serialize(): array{ return MagicClass::__serialize(); }

    public function __unserialize($content): void { MagicClass::__unserialize($content); }

    public function __toString(): string { return MagicClass::__toString(); }

    public function __invoke(): void { MagicClass::__invoke(); }

    public function __clone() { MagicClass::__clone(); }

    public function __debugInfo(): array { return MagicClass::__debugInfo(); }
}
