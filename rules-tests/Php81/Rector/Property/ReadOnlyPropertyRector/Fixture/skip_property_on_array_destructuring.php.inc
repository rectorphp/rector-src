<?php

namespace Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\Fixture;

final class Vcs
{
    public function __construct(
        private readonly string $accessToken,
        private readonly string $repository,
    ) {
    }
    
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }
    
    public function getRepository(): string
    {
        return $this->repository;
    }
}

namespace Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\Fixture;

final class DemoFile
{
    public function __construct(
        private ?string $accessToken = null,
        private ?string $owner = null,
        private ?string $name = null,
    ) {
    }
    
    public function withVcs(Vcs $vcs): static
    {
        $clone = clone $this;
        $clone->accessToken = $vcs->getAccessToken();
        [$clone->owner, $clone->name] = explode('/', $vcs->getRepository(), 2);
        
        return $clone;
    }
}

?>
