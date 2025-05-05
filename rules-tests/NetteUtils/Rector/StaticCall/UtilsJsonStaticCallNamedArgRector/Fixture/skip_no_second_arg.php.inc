<?php

namespace Rector\Tests\NetteUtils\Rector\StaticCall\UtilsJsonStaticCallNamedArgRector\Fixture;

use Nette\Utils\Json;

final class SkipNoSecondArg
{
    public function run($data, $json)
    {
        $encodedJson = Json::encode($data);
        $decodedJson = Json::decode($json);
    }
}
