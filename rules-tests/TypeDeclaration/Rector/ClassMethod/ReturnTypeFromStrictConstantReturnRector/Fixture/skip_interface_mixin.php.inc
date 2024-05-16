<?php

// the namespace Webmozart\Assert on purpose to reproduce crash
// @see https://github.com/rectorphp/rector/issues/8624
// @see https://getrector.com/demo/e6b2e871-1b41-4897-8864-2f9aaad48de1
namespace Webmozart\Assert;

interface Mixin
{
    public static function nullOrString($value, $message = '');
}
