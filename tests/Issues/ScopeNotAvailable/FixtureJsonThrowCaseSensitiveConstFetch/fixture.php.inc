<?php

namespace Rector\Core\Tests\Issues\ScopeNotAvailable\FixtureJsonThrowCaseSensitiveConstFetch;

class Fixture
{
    public static function run()
    {
        $user = $_REQUEST['param'];

        $user = json_decode($user);

        $condition = true;
        if ($condition) {
            $output = [];
            echo(json_encode($output));
            exit;
        }

        $output = [];
        echo(json_encode($output));
    }
}

?>
-----
<?php

namespace Rector\Core\Tests\Issues\ScopeNotAvailable\FixtureJsonThrowCaseSensitiveConstFetch;

class Fixture
{
    public static function run()
    {
        $user = $_REQUEST['param'];

        $user = json_decode($user, null, 512, JSON_THROW_ON_ERROR);

        $condition = true;
        if ($condition) {
            $output = [];
            echo(json_encode($output));
            exit;
        }

        $output = [];
        echo(json_encode($output, JSON_THROW_ON_ERROR));
    }
}

?>
