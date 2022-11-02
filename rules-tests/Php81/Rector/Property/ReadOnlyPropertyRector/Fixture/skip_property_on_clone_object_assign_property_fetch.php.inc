<?php

namespace Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\Fixture;

final class SkipPropertyOnCloneObjectAssignPropertyFetch
{
    public function __construct(
        private ?string $accessToken = null,
        private ?string $owner = null,
        private ?string $name = null,
    ) {
    }

    public function withVcs(string $value)
    {
        $clone = clone $this;
        $clone->accessToken = $value;
        [$clone->owner, $clone->name] = explode('/', $value, 2);

        return $clone;
    }
}

?>
