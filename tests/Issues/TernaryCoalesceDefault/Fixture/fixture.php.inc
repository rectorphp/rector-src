<?php

namespace Rector\Core\Tests\Issues\TernaryCoalesceDefault\Fixture;

class Fixture
{
    public function run()
    {
        $http = env('HTTP_X');
        return ($http === null) ? false : $http;
    }
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\TernaryCoalesceDefault\Fixture;

class Fixture
{
    public function run()
    {
        $http = env('HTTP_X');
        return $http ?? false;
    }
}

?>
