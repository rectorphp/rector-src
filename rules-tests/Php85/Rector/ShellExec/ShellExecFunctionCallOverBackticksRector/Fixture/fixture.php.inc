<?php

namespace Rector\Tests\Php85\Rector\ShellExec\ShellExecFunctionCallOverBackticksRector\Fixture;

class Fixture
{
    public function run()
    {
        $output = `ls -al`;
        echo "<pre>$output</pre>";
    }
}

?>
-----
<?php

namespace Rector\Tests\Php85\Rector\ShellExec\ShellExecFunctionCallOverBackticksRector\Fixture;

class Fixture
{
    public function run()
    {
        $output = shell_exec('ls -al');
        echo "<pre>$output</pre>";
    }
}

?>