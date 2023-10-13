<?php

namespace Rector\Tests\DeadCode\Rector\Assign\RemoveUnusedVariableAssignRector\Fixture;

class OnFileGetContents {
    public function run(array $params)
    {
        $return = file_get_contents("http://example.com");

        var_dump($http_response_header);
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Assign\RemoveUnusedVariableAssignRector\Fixture;

class OnFileGetContents {
    public function run(array $params)
    {
        file_get_contents("http://example.com");

        var_dump($http_response_header);
    }
}

?>
