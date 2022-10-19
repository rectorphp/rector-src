<?php

namespace Rector\Tests\Php70\Rector\MethodCall\ThisCallOnStaticMethodToStaticCallRector\Fixture;

final class SkipFirstClassCallable
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('top_level_domain', $this->topLevelDomain(...)),
        ];
    }

    public static function topLevelDomain(?string $host = null): string
    {
        if (null === $host) {
            $host = $_SERVER['HTTP_HOST'];
        }

        $urlPieces = explode('.', (string) $host);

        return end($urlPieces);
    }
}
