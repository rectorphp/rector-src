<?php

namespace Rector\Tests\Issues\ScopeNotAvailable\FixtureJsonThrowCaseSensitiveConstFetch;

class Fixture
{
    public static function run()
    {
        $user = $_REQUEST['param'];

        // add JSON_THROW_ON_ERROR on NON-exact value
        $user = json_decode($user);

        $condition = true;
        if ($condition) {
            $output = [];
            // skip add JSON_THROW_ON_ERROR on exact value
            echo(json_encode($output));
            exit;
        }

        $output = [];
        // skip add JSON_THROW_ON_ERROR on exact value
        echo(json_encode($output));
    }
}

?>
-----
<?php

namespace Rector\Tests\Issues\ScopeNotAvailable\FixtureJsonThrowCaseSensitiveConstFetch;

class Fixture
{
    public static function run()
    {
        $user = $_REQUEST['param'];

        // add JSON_THROW_ON_ERROR on NON-exact value
        $user = json_decode($user, null, 512, JSON_THROW_ON_ERROR);

        $condition = true;
        if ($condition) {
            $output = [];
            // skip add JSON_THROW_ON_ERROR on exact value
            echo(json_encode($output));
            exit;
        }

        $output = [];
        // skip add JSON_THROW_ON_ERROR on exact value
        echo(json_encode($output));
    }
}

?>
