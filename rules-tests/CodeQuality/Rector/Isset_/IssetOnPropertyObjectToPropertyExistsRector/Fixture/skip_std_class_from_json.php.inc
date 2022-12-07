<?php

namespace Rector\Tests\CodeQuality\Rector\Isset_\IssetOnPropertyObjectToPropertyExistsRector\Fixture;

final class SkipStdClassFromJson
{
    public function run(string $contents) {
        $result = json_decode($contents);
        return isset($result->key->value);
    }
}
