<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\FixturePhp80;

// future note: this 2 classes here on purpose to reproduce crash
// see https://github.com/rectorphp/rector/issues/8600

/**
 * @method static mixed get(array|string $key, mixed|\Closure $default = null)
 *
 * @mixin \Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedCallRector\Source\Repository
 */
class Cache
{
}

class SkipWithMixin
{
    /**
     * @throws GuzzleException
     * @throws JsonException
     */
    protected function getOauthToken(string $key)
    {
		 return Cache::get($key);
    }
}
