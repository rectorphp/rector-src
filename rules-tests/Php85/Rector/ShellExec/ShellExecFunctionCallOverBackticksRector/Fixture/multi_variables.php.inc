<?php

namespace Rector\Tests\Php85\Rector\ShellExec\ShellExecFunctionCallOverBackticksRector\Fixture;

class MultiVariables
{
    public function run()
    {
        $a = 'X';
        $b = 'Y';
        $output = `echo $a ' and ' $b`;

        echo $output;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php85\Rector\ShellExec\ShellExecFunctionCallOverBackticksRector\Fixture;

class MultiVariables
{
    public function run()
    {
        $a = 'X';
        $b = 'Y';
        $output = shell_exec('echo ' . $a .  ' and ' . $b);

        echo $output;
    }
}

?>
