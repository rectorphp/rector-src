<?php

namespace Rector\Tests\NetteUtils\Rector\StaticCall\UtilsJsonStaticCallNamedArgRector\Fixture;

use Nette\Utils\Json;

final class SomeStaticCalls
{
    public function run($data, $json)
    {
        $encodedJson = Json::encode($data, true);
        $decodedJson = Json::decode($json, true);
    }
}

?>
-----
<?php

namespace Rector\Tests\NetteUtils\Rector\StaticCall\UtilsJsonStaticCallNamedArgRector\Fixture;

use Nette\Utils\Json;

final class SomeStaticCalls
{
    public function run($data, $json)
    {
        $encodedJson = Json::encode($data, pretty: true);
        $decodedJson = Json::decode($json, forceArrays: true);
    }
}

?>
