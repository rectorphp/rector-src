<?php

namespace Rector\Tests\CodingStyle\Rector\Encapsed\EncapsedStringsToSprintfRector\Fixture;

final class OnlyTwoConcat
{
    public function run()
    {
        $url = "{$host}{$requestUri}";
        return $url;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\Encapsed\EncapsedStringsToSprintfRector\Fixture;

final class OnlyTwoConcat
{
    public function run()
    {
        $url = $host . $requestUri;
        return $url;
    }
}

?>
