<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\ReturnTypeDeclarationRector\FixtureForPhp81;

final class SkipIntersectionArray
{
    public function setAuthorizationHeader(array $config): array
    {
        if (!isset($config['headers'])) {
            $config['headers'] = [];
        }

        return $config;
    }
}
