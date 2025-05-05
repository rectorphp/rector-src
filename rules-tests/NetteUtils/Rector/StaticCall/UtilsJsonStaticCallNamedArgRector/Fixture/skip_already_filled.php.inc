<?php

namespace Rector\Tests\NetteUtils\Rector\StaticCall\UtilsJsonStaticCallNamedArgRector\Fixture;

use Nette\Utils\Json;

final class SkipAlreadyFilled
{
    public function run($data, $json)
    {
        $encodedJson = Json::encode($data, pretty: true);
        $decodedJson = Json::decode($json, forceArrays: true);
    }
}
