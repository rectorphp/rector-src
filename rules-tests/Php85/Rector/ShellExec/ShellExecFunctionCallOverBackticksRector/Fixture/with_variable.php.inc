<?php

namespace Rector\Tests\Php85\Rector\ShellExec\ShellExecFunctionCallOverBackticksRector\Fixture;

class WithVariable
{
    public function run()
    {
        $dir = __DIR__;
        $var = 'example';

        $output = `ls -al`;
        $output2 = `ls $dir`;
        $output3 = `echo "value: $var"`;
        echo "<pre>$output</pre>";
        echo "<pre>$output2</pre>";
        echo "<pre>$output3</pre>";
    }
}

?>
-----
<?php

namespace Rector\Tests\Php85\Rector\ShellExec\ShellExecFunctionCallOverBackticksRector\Fixture;

class WithVariable
{
    public function run()
    {
        $dir = __DIR__;
        $var = 'example';

        $output = shell_exec("ls -al");
        $output2 = shell_exec("ls $dir");
        $output3 = shell_exec("echo \"value: $var\"");
        echo "<pre>$output</pre>";
        echo "<pre>$output2</pre>";
        echo "<pre>$output3</pre>";
    }
}

?>
