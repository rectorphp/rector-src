<?php

namespace Rector\Tests\CodingStyle\Rector\Encapsed\EncapsedStringsToSprintfRector\FixtureAlwaysSprintf;

final class OnlyThreeConcat
{
    public function run()
    {
        $url = "{$host}{$requestUri}{$more}";
        return $url;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\Encapsed\EncapsedStringsToSprintfRector\FixtureAlwaysSprintf;

final class OnlyThreeConcat
{
    public function run()
    {
        $url = $host . $requestUri . $more;
        return $url;
    }
}

?>
